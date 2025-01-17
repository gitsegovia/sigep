<?php
/* SVN FILE: $Id: scaffold.php,v 1.1 2009-10-14 03:01:19 sigep Exp $ */
/**
 * Scaffold.
 *
 * Automatic forms and actions generation for rapid web application development.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.controller
 * @since			Cake v 0.10.0.1076
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:19 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Scaffolding is a set of automatic views, forms and controllers for starting web development work faster.
 *
 * Scaffold inspects your database tables, and making educated guesses, sets up a
 * number of pages for each of your Models. These pages have data forms that work,
 * and afford the web developer an early look at the data, and the possibility to over-ride
 * scaffolded actions with custom-made ones.
 *
 * @package		cake
 * @subpackage	cake.cake.libs.controller
 */
class Scaffold extends Object{
/**
 *  Name of view to render
 *
 * @var string
 */
	 var $actionView = null;
/**
 * Class name of model
 *
 * @var unknown_type
 */
	 var $modelKey = null;
/**
 * Controller object
 *
 * @var Controller
 */
	 var $controllerClass = null;
/**
 * Name of scaffolded Model
 *
 * @var string
 */
	 var $modelName = null;
/**
 * Title HTML element for current scaffolded view
 *
 * @var string
 */
	 var $scaffoldTitle = null;
/**
 * Base URL
 *
 * @var string
 */
	 var $base = false;
/**
 * Construct and set up given controller with given parameters.
 *
 * @param object $controller instance of controller
 * @param array $params
 */
	function __construct(&$controller, $params) {
		$this->controllerClass =& $controller;
		$this->actionView = $controller->action;
		$this->modelKey  = ucwords(Inflector::singularize($controller->name));
		$this->scaffoldTitle = Inflector::humanize($this->modelKey);
		$this->viewPath = Inflector::underscore($controller->name);
		$this->controllerClass->pageTitle = $this->scaffoldTitle;
		$this->controllerClass->pageTitle = 'Scaffold :: ' . Inflector::humanize($controller->action) . ' :: ' .
															Inflector::humanize(Inflector::pluralize($this->modelKey));
		$this->__scaffold($params);
	}
/**
 * Renders a view view of scaffolded Model.
 *
 * @param array $params
 * @return A rendered view of a row from Models database table
 * @access private
 */
	function __scaffoldView($params) {
		if ($this->controllerClass->_beforeScaffold('view')) {

			if(isset($params['pass'][0])){
				$this->controllerClass->{$this->modelKey}->id = $params['pass'][0];

			} elseif (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
				$this->controllerClass->Session->setFlash('No id set for ' . Inflector::humanize($this->modelKey) . '::view().');
				$this->controllerClass->redirect('/' . Inflector::underscore($this->controllerClass->viewPath));

			} else {
				return $this->controllerClass->flash('No id set for ' . Inflector::humanize($this->modelKey) . '::view().',
																		'/' . Inflector::underscore($this->controllerClass->viewPath));
			}

			$this->controllerClass->params['data'] = $this->controllerClass->{$this->modelKey}->read();
			$this->controllerClass->set('data', $this->controllerClass->params['data']);
			$this->controllerClass->set('fieldNames',$this->controllerClass->generateFieldNames(
													$this->controllerClass->params['data'], false));

			if (file_exists(APP . 'views' . DS . $this->viewPath . DS . 'scaffold.view.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . $this->viewPath . DS . 'scaffold.view.thtml');

			} elseif (file_exists(APP . 'views' . DS . 'scaffold' . DS . 'scaffold.view.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . 'scaffold' . DS . 'scaffold.view.thtml');
			} else {
				return $this->controllerClass->render($this->actionView, '',
																	LIBS . 'view' . DS . 'templates' . DS . 'scaffolds' . DS . 'view.thtml');
			}
		} elseif ($this->controllerClass->_scaffoldError('view') === false) {
			return $this->__scaffoldError();
		}
	}
/**
 * Renders List view of scaffolded Model.
 *
 * @param array $params
 * @return A rendered view listing rows from Models database table
 * @access private
 */
	function __scaffoldIndex($params) {
		if ($this->controllerClass->_beforeScaffold('index')) {
			$this->controllerClass->set('fieldNames', $this->controllerClass->generateFieldNames(null, false));
			$this->controllerClass->{$this->modelKey}->recursive = 0;
			$this->controllerClass->set('data', $this->controllerClass->{$this->modelKey}->findAll());

			if (file_exists(APP . 'views' . DS . $this->viewPath . DS . 'scaffold.index.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . $this->viewPath . DS . 'scaffold.index.thtml');

			} elseif (file_exists(APP . 'views' . DS . 'scaffold' . DS . 'scaffold.index.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . 'scaffold' . DS . 'scaffold.index.thtml');
			} else {
				return $this->controllerClass->render($this->actionView, '',
																	LIBS . 'view' . DS . 'templates' . DS . 'scaffolds' . DS . 'index.thtml');
			}
		} elseif ($this->controllerClass->_scaffoldError('index') === false) {
			return $this->__scaffoldError();
		}
	}
/**
 * Renders an Add or Edit view for scaffolded Model.
 *
 * @param array $params
 * @param string $params add or edit
 * @return A rendered view with a form to edit or add a record in the Models database table
 * @access private
 */
	function __scaffoldForm($params = array(), $type) {
		$thtml = 'edit';
		$form = 'Edit';

		if ($type === 'add') {
			$thtml = 'add';
			$form = 'Add';
		}

		if ($this->controllerClass->_beforeScaffold($type)) {
			if ($type == 'edit') {

				if(isset($params['pass'][0])){
					$this->controllerClass->{$this->modelKey}->id = $params['pass'][0];

				} elseif (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
					$this->controllerClass->Session->setFlash('No id set for ' . Inflector::humanize($this->modelKey) . '::edit().');
					$this->controllerClass->redirect('/' . Inflector::underscore($this->controllerClass->viewPath));

				} else {
					return $this->controllerClass->flash('No id set for ' . Inflector::humanize($this->modelKey) . '::edit().',
																		'/' . Inflector::underscore($this->controllerClass->viewPath));
				}

				$this->controllerClass->params['data']=$this->controllerClass->{$this->modelKey}->read();
				$this->controllerClass->set('fieldNames', $this->controllerClass->generateFieldNames(
																		$this->controllerClass->params['data']));
				$this->controllerClass->set('data', $this->controllerClass->params['data']);
			} else {
				$this->controllerClass->set('fieldNames', $this->controllerClass->generateFieldNames());
			}
			$this->controllerClass->set('type', $form);

			if (file_exists(APP . 'views' . DS . $this->viewPath . DS . 'scaffold.' . $thtml . '.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . $this->viewPath . DS . 'scaffold.' . $thtml . '.thtml');
			} elseif (file_exists(APP . 'views' . DS . 'scaffold' . DS . 'scaffold.' . $thtml . '.thtml')) {
				return $this->controllerClass->render($this->actionView, '',
																	APP . 'views' . DS . 'scaffold' . DS . 'scaffold.' . $thtml . '.thtml');
			} else {
				return $this->controllerClass->render($this->actionView, '',
																	LIBS . 'view' . DS . 'templates' . DS . 'scaffolds' . DS . 'edit.thtml');
			}
		} elseif ($this->controllerClass->_scaffoldError($type) === false) {
			return $this->__scaffoldError();
		}
	}
/**
 * Saves or updates a model.
 *
 * @param array $params
 * @param string $type create or update
 * @return success on save/update, add/edit form if data is empty or error if save or update fails
 * @access private
 */
	function __scaffoldSave($params = array(), $type) {
		$thtml = 'edit';
		$form = 'Edit';
		$success = 'updated';
		$formError = 'edit';

		if ($this->controllerClass->_beforeScaffold($type)) {
			if (empty($this->controllerClass->params['data'])) {
				if ($type === 'create') {
					$formError = 'add';
				}
				return $this->__scaffoldForm($params, $formError);
			}
			$this->controllerClass->set('fieldNames', $this->controllerClass->generateFieldNames());
			$this->controllerClass->cleanUpFields();

			if ($type == 'create') {
				$this->controllerClass->{$this->modelKey}->create();
				$thtml = 'add';
				$form = 'Add';
				$success = 'saved';
			}

			if ($this->controllerClass->{$this->modelKey}->save($this->controllerClass->params['data'])) {

				if ($this->controllerClass->_afterScaffoldSave($type)) {

					if (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
						$this->controllerClass->Session->setFlash('The ' . Inflector::humanize($this->modelKey) . ' has been ' . $success . '.');
						$this->controllerClass->redirect('/' . Inflector::underscore($this->controllerClass->viewPath));
					} else {
						return $this->controllerClass->flash('The ' . Inflector::humanize($this->modelKey) . ' has been ' . $success . '.',
																			'/' . Inflector::underscore($this->controllerClass->viewPath));
					}
				} else {
					return $this->controllerClass->_afterScaffoldSaveError($type);
				}
			} else {
				if (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
					$this->controllerClass->Session->setFlash('Please correct errors below.');
				}
				$this->controllerClass->set('data', $this->controllerClass->params['data']);
				$this->controllerClass->set('fieldNames',
														$this->controllerClass->generateFieldNames($this->__rebuild($this->controllerClass->params['data'])));
				$this->controllerClass->validateErrors($this->controllerClass->{$this->modelKey});
				$this->controllerClass->set('type', $form);

				if (file_exists(APP . 'views' . DS . $this->viewPath . DS . 'scaffolds' . DS . 'scaffold.' . $thtml . '.thtml')) {
					return $this->controllerClass->render($this->actionView, '',
																		APP . 'views' . DS . $this->viewPath . DS . 'scaffolds' . DS . 'scaffold.' . $thtml . '.thtml');
				} elseif (file_exists(APP . 'views' . DS . 'scaffold' . DS . 'scaffold.' . $thtml . '.thtml')) {
					return $this->controllerClass->render($this->actionView, '',
																		APP . 'views' . DS . 'scaffold' . DS . 'scaffold.' . $thtml . '.thtml');
				} else {
					return $this->controllerClass->render($this->actionView, '',
																		LIBS . 'view' . DS . 'templates' . DS . 'scaffolds' . DS . 'edit.thtml');
				}
			}
		} elseif ($this->controllerClass->_scaffoldError($type) === false) {
			return $this->__scaffoldError();
		}
	}
/**
 * Performs a delete on given scaffolded Model.
 *
 * @param array $params
 * @return success on delete error if delete fails
 * @access private
 */
	function __scaffoldDelete($params = array()) {
		if ($this->controllerClass->_beforeScaffold('delete')) {
			$id = $params['pass'][0];

			if ($this->controllerClass->{$this->modelKey}->del($id)) {

				if (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
					$this->controllerClass->Session->setFlash('The ' . Inflector::humanize($this->modelKey) . ' with id: ' . $id . ' has been deleted.');
					$this->controllerClass->redirect('/' . Inflector::underscore($this->controllerClass->viewPath));
				} else {
					return $this->controllerClass->flash('The ' . Inflector::humanize($this->modelKey) . ' with id: ' . $id . ' has been deleted.',
																		'/' . Inflector::underscore($this->controllerClass->viewPath));
				}
			} else {
				if (isset($this->controllerClass->Session) && $this->controllerClass->Session->valid != false) {
					$this->controllerClass->Session->setFlash('There was an error deleting the ' . Inflector::humanize($this->modelKey) . ' with the id ' . $id);
					$this->controllerClass->redirect('/' . Inflector::underscore($this->controllerClass->viewPath));
				} else {
					return $this->controllerClass->flash('There was an error deleting the ' . Inflector::humanize($this->modelKey) . ' with the id ' . $id,
																		'/' . Inflector::underscore($this->controllerClass->viewPath));
				}
			}
		} elseif ($this->controllerClass->_scaffoldError('delete') === false) {
			return $this->__scaffoldError();
		}
	}
/**
 * Enter description here...
 *
 * @return unknown
 */
	function __scaffoldError() {
		if (file_exists(APP . 'views' . DS . $this->viewPath . DS . 'scaffolds' . DS . 'scaffold.error.thtml')) {
			return $this->controllerClass->render($this->actionView, '',
																APP . 'views' . DS . $this->viewPath	. DS . 'scaffolds' . DS . 'scaffold.error.thtml');
		} elseif (file_exists(APP . 'views' . DS . 'scaffold' . DS . 'scaffold.error.thtml')) {
			return $this->controllerClass->render($this->actionView, '',
																APP . 'views' . DS . 'scaffold' . DS . 'scaffold.error.thtml');
		} else {
			return $this->controllerClass->render($this->actionView, '',
																LIBS . 'view' . DS . 'templates' . DS . 'errors' . DS . 'scaffold_error.thtml');
		}
	}
/**
 * When forms are submited the arrays need to be rebuilt if
 * an error occured, here the arrays are rebuilt to structure needed
 *
 * @param array $params data passed to forms
 * @return array rebuilds the association arrays to pass back to Controller::generateFieldNames()
 */
	function __rebuild($params) {
		foreach($params as $model => $field) {
			if (!empty($field) && is_array($field)) {
				$match = array_keys($field);

				if ($model == $match[0]) {
					$count = 0;

					foreach($field[$model] as $value) {
						$params[$model][$count][$this->controllerClass->{$this->modelKey}->{$model}->primaryKey] = $value;
						$count++;
					}
					unset ($params[$model][$model]);
				}
			}
		}
		return $params;
	}
/**
 * When methods are now present in a controller
 * scaffoldView is used to call default Scaffold methods if:
 * <code>
 * var $scaffold;
 * </code>
 * is placed in the controller's class definition.
 *
 * @param string $url
 * @param string $controller_class
 * @param array $params
 * @since Cake v 0.10.0.172
 * @access private
 */
	function __scaffold($params) {
		if (!in_array('Form', $this->controllerClass->helpers)) {
			$this->controllerClass->helpers[] = 'Form';
		}
		if($this->controllerClass->constructClasses()){
			$db =& ConnectionManager::getDataSource($this->controllerClass->{$this->modelKey}->useDbConfig);

			if (isset($db)) {
				if ($params['action'] === 'index' || $params['action'] === 'list' || $params['action'] === 'view'
						|| $params['action'] === 'add' || $params['action'] === 'create'
						|| $params['action'] === 'edit' || $params['action'] === 'update'
						|| $params['action'] === 'delete') {

					switch($params['action']) {
						case 'index':
							$this->__scaffoldIndex($params);
						break;
						case 'view':
							$this->__scaffoldView($params);
						break;
						case 'list':
							$this->__scaffoldIndex($params);
						break;
						case 'add':
							$this->__scaffoldForm($params, 'add');
						break;
						case 'edit':
							$this->__scaffoldForm($params, 'edit');
						break;
						case 'create':
							$this->__scaffoldSave($params, 'create');
						break;
						case 'update':
							$this->__scaffoldSave($params, 'update');
						break;
						case 'delete':
							$this->__scaffoldDelete($params);
						break;
					}
				} else {
					return $this->cakeError('missingAction',
													array(array('className' => Inflector::camelize($params['controller'] . "Controller"),
																	'base' => $this->controllerClass->base,
																	'action' => $params['action'],
																	'webroot' => $this->controllerClass->webroot)));
				}
			} else {
				return $this->cakeError('missingDatabase', array(array('webroot' => $this->controllerClass->webroot)));
			}
		} else {
			return $this->cakeError('missingModel', array(array('className' => $this->modelKey, 'webroot' => '', 'base' => $this->base)));
		}
	}
}
?>