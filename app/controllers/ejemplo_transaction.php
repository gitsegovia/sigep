<?php
class OrderController extends AppController {
    var $name = 'Order';
    var $uses = array('Order');

    function checkout() {
        if(!empty($this->data)) {
            $this->Order->begin(); // Start our transaction
            if( $this->Order->save( $this->data ) ) {
                if( $this->Order->OrderDetail->save( $this->data ) ) {
                    $this->Order->commit(); // Persist the data
                    $this->redirect('/shop/order/thanks');
                    exit;
                } else {
// Couldnt save the products to the order
// since the products to the order couldnt be stored, dont store anything at all
// rollback all changes to the database sine 'begin' was called
                    $this->Order->rollback();
                    $this->redirect('/shop/order/error');
                    exit;
                }
            } else {
                // Didnt save
            }
        }
    }
}
?>