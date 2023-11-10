<?php
//app::import('Vendor', 'open-flash-chart');
vendor('open-flash-chart');
/**
 *
 **/
class FlashChartHelper extends Helper {

	var $helpers = array('Html','Javascript');

	var $graph = null;
	var $hasGlass = false;

	function begin($width=null, $height=null) {
		$this->graph = new graph();
		$this->graph->js_path	= $this->Html->url('/') . 'js/';
		$this->graph->swf_path	= $this->Html->url('/');
		$this->graph->set_output_type('js');
		$this->graph->set_bg_colour('#ffffff');
		$this->hasGlass = false;
		if ($width!=null) {
			$this->graph->set_width($width);
		}
		if ($height!=null) {
			$this->graph->set_height($height);
		}
		$this->setToolTip();
	}

	function setToolTip($tooltip = '') {
		$this->graph->set_tool_tip($tooltip);
	}

	function title($title='', $options = array('font-size' => '14px', 'color'=>'#333333')) {
		$options = $this->__prepareOptions($options);
		$this->graph->title($title, $options);
	}

	function setData($data_sets) {

		foreach ($data_sets as $legend => $info) {

			$width		= $this->__prepareValue(3, 'width', $info);
			$color		= $this->__prepareValue('#000000', 'color', $info);
			$font_size	= $this->__prepareValue(10, 'font_size', $info);
			$circle		= $this->__prepareValue(-1, 'circle', $info);
			$alpha = $this->__prepareValue(60, 'alpha', $info);

			$data = $info['data'];
			if (isset($info['format'])) {
				$data = Set::extract($data, $info['format']);
			}
			$graph_style = $this->__prepareValue('', 'graph_style', $info);
			if ($graph_style!='scatter') {
				$this->graph->set_data($data);
			}
			switch ($graph_style) {
				case 'scatter':
					$this->__scatter(array($legend => $info));
					break;
				case 'bar_sketch':
					$offset = $this->__prepareValue(4, 'offset', $info);
					$alpha = $this->__prepareValue(60, 'alpha', $info);
					list($r,$g,$b) = $this->__html2rgb($color);
					$outline_color = $this->__prepareValue($this->__rgb2html($r-40,$g-40,$b-40), 'outline_color', $info);
					$this->graph->bar_sketch($alpha, $offset, $color, $outline_color, $legend, $font_size);
					break;
				case 'bar_glass':
					$this->hasGlass = true;
				case 'bar_filled':
					$alpha = $this->__prepareValue(60, 'alpha', $info);
					list($r,$g,$b) = $this->__html2rgb($color);
					$outline_color = $this->__prepareValue($this->__rgb2html($r-40,$g-40,$b-40), 'outline_color', $info);
					$this->graph->{"$graph_style"}($alpha, $color, $outline_color, $legend, $font_size);
					break;
				case 'bar_3D':
					$axis_3d_height = $this->__prepareValue(10, 'axis_3d_height', $info);
					$this->graph->set_x_axis_3d($axis_3d_height);
					$default_alpha = 90;
				case 'bar_fade':
					if (!isset($default_alpha)) {
						$default_alpha = 70;
					}
				case 'bar':
					if (!isset($default_alpha)) {
						$default_alpha = 60;
					}
					$alpha = $this->__prepareValue($default_alpha, 'alpha', $info);
					$this->graph->{"$graph_style"}($alpha, $color, $legend, $font_size);
					break;
				case 'line_hollow':
					$this->graph->line_hollow($width, $width+2, $color, $legend, $font_size);
					break;
				case 'line_dot':
					$this->graph->line_dot($width, $width+2, $color, $legend, $font_size);
					break;
				default:
					$this->graph->line($width, $color, $legend, $font_size, $circle);
			}

		}
	}

	function __scatter($info) {
		$legend 	= key($info);
		$info		= current($info);

		$width		= $this->__prepareValue(1, 'width', $info);
		$color		= $this->__prepareValue('#000000', 'color', $info);
		$font_size	= $this->__prepareValue(10, 'font_size', $info);

		$alpha = $this->__prepareValue(60, 'alpha', $info);
		foreach ($info['data'] as $i => $point) {
			$dot_width = $this->__prepareValue(4, 'dot_width', $point);
			$data[$i] = new point($point['x'], $point['y'], $dot_width);
		}
		$this->graph->scatter($data, $width, $color, $legend, $font_size);
	}

	function pie($data, $alpha=60, $line_color = '#505050', $style = array('font-size' => '12px', 'color' => '#333333')) {
		$style = $this->__prepareOptions($style);
		$this->graph->pie($alpha, $line_color, $style);
		$r = rand(100,200);
		$g = rand(0,100);
		$b = rand(200,255);
		$which = $r%3;
		$labels = array();
		foreach ($data as $label => $value) {
			$labels[] = $label;
			if (isset($value['color'])) continue;
			$data[$label]['color'] = $this->__rgb2html($r, $g, $b);
			switch ($which) {
				case 0:
					$r = ($r+100)%255;
					break;
				case 1:
					$g = ($g+100)%255;
					break;
				case 2:
					$b = ($b+100)%255;
					break;
			}
			$which = ($which+1)%3;
		}
		$values = array_values(Set::extract($data, '{}.value'));
		$this->graph->pie_values($values, $labels);
		$colors = array_values(Set::extract($data, '{}.color'));
		$this->graph->pie_slice_colours($colors);
		$this->setToolTip('#x_label#<br>#val#%');
	}

	function setLabels($axis, $labels = array()) {
		if ($axis=='x' || $axis=='y') {
			$this->graph->{"set_{$axis}_labels"}($labels);
		}
	}

	function setRange($axis, $min=null, $max=null) {
		if ($axis=='x' || $axis=='y') {
			if ($min!=null) {
				$this->graph->{"set_{$axis}_min"}($min);
			}
			if ($max!=null) {
				$this->graph->{"set_{$axis}_max"}($max);
			}
		}
	}

	function setStep($axis, $step) {
		if ($axis=='x' || $axis=='y') {
			$this->graph->{"{$axis}_label_step"}($step);
		}
	}

	function configureGrid($styles) {
		$this->__setAxisStyle('x', $styles);
		$this->__setAxisStyle('y', $styles);
		$this->setBackgroundColor('#ffffff');
	}

	function setBackgroundColor($color) {
		$this->graph->set_bg_colour($color);
	}

	function __setAxisStyle($axis, $styles) {
		if ($axis=='x' || $axis=='y') {
			if (isset($styles[$axis.'_axis'])) {
				$style_info = $styles[$axis.'_axis'];
				$size			= $this->__prepareValue(9, 'size', $style_info);
				$color			= $this->__prepareValue('#666666', 'color', $style_info);
				$orientation	= $this->__prepareValue(0, 'orientation', $style_info); // Only works for x
				$step			= $this->__prepareValue(-1, 'step', $style_info);
				$grid_marks		= $this->__prepareValue('#000000', 'grid_marks', $style_info);
				$this->graph->{"set_{$axis}_label_style"}($size, $color, $orientation, $step, $grid_marks);

				$grid			= $this->__prepareValue('#dddddd', 'grid', $style_info);
				$this->graph->{"{$axis}_axis_colour"}($color, $grid);

				$legend			= $this->__prepareValue('', 'legend', $style_info);
				$legend_size	= $this->__prepareValue(12,  'legend_size', $style_info);
				$legend_color	= $this->__prepareValue($color, 'legend_color',	$style_info);
				$this->graph->{"set_{$axis}_legend"}($legend, $legend_size, $legend_color);

			}
		}
	}

	function __prepareOptions($options) {
		$options_str = '';
		foreach($options as $key => $value) {
			$options_str .= $key . ':' . $value . ';';
		}
		return '{' . $options_str . '}';
	}

	function __prepareValue($default, $key, $arr) {
		$value = $default;
		if (isset($arr[$key])) {
			$value = $arr[$key];
		}
		return $value;
	}

	function render() {
		if ($this->hasGlass) {
			$this->graph->x_axis_3d = '';
		}
		return $this->graph->render();
	}

	/**
	 * @see http://www.anyexample.com/programming/php/php_convert_rgb_from_to_html_hex_color.xml
	 */
	function __html2rgb($color) {
		if ($color[0] == '#')
			$color = substr($color, 1);
		if (strlen($color) == 6)
			list($r, $g, $b) = array(
				$color[0].$color[1],
				$color[2].$color[3],
				$color[4].$color[5]
			);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return array($r, $g, $b);
	}

	/**
	 * @see http://www.anyexample.com/programming/php/php_convert_rgb_from_to_html_hex_color.xml
	 */
	function __rgb2html($r, $g=-1, $b=-1){
		if (is_array($r) && sizeof($r) == 3)
			list($r, $g, $b) = $r;

		$r = intval($r);
		$g = intval($g);
		$b = intval($b);

		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));

		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
	}
}
?>