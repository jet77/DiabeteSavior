<?php
/**
 * User controller.
 *
 * This file will render views from views/caculators/
 *
 * PHP 5
 *
 * Copyright (c) Jason Lu (jasonl.biz@gmail.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Jason Lu (jasonl.biz@gmail.com)
 * @link          https://github.com/bumetcs/cs673
 * @package       app.Controller
 * @since         DiabeteSavior v 0.0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');
App::uses('Security', 'Utility');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
  public $uses = array();

  public function beforeFilter(){
    parent::beforeFilter();
    Security::setHash('sha512');
  }

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function index() {
    $this->authenticate_user();
    $users = $this->User->find('all');
    $this->set('users', $users);
  }


/**
 * Do login
 *
 * @param email, password
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function do_login() {
    //var_dump($this->request->data); exit;
    if(!$this->request->is('post') && !$this->request->is('put')) {
      //$this->header('HTTP/1.1 403 Forbidden');
      //$this->Session->setFlash(__('Go away'));
      throw new ForbiddenException();
      //$this->cakeError('error403');
      //exit;
    }

    $email = $this->request->data('email');
    $password = $this->request->data('password');
    $conditions = array("email" => $email, "password" => $password);
    $user = $this->User->find('first', array('conditions' => $conditions));
    $user = $user['User'];
    $res = new stdClass();
    $res->status = 0;
    $res->message = "Initialized";
    $res->data = null;
    if (!$user) {
      $res->status = -1;
      $res->message = __d("login", "Login failed, wrong email/password");
      $res->data = $user;
    } else {
      $res->status = 1;
      $res->message = __d("login", "Login succeed");
      $res->data = $user;
      $this->Session->write('user', $user);
    }
    $this->set('user', $user);
    $this->autoRender = false;
    $this->redirect($this->referer());
    //echo json_encode($res);
    exit;
  }

/**
 * Do logout
 *
 * @param email, password
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function logout() {
    $this->Session->delete('user');
    $this->redirect("/");
    exit;
  }

/**
 * Create new user
 *
 * @param email, password
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function add() {
    //$this->authenticate_user();
    if ($this->request->is('post')){
      $this->User->create();
      try {
        $res = $this->User->saveAssociated($this->request->data);
        $this->redirect("/pages/after_sign_up");
      } catch(Exception $e) {
        $this->Session->setFlash($e->getMessage());
        $this->redirect("/sign_up");
      }
    } else {
      throw new ForbiddenException();
    }
  }
/**
 * Activate user
 *
 * @param id
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function activate() {
    $this->authenticate_user();
    if($this->can('manage', 'User')) {
      $id = $this->request->params['pass'][0];
      $this->User->id = $id;
      $data = array('activated' => 1);
      $this->User->save($data);
      $this->redirect($this->referer());
      //$this->render("/layouts/debug");
    } else {
      throw new ForbiddenException();
      
    }
  }

/**
 * Deactivate user
 *
 * @param id
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function deactivate() {
    $this->authenticate_user();
    if($this->can('manage', 'User')) {
      $id = $this->request->params['pass'][0];
      $this->User->id = $id;
      $data = array('activated' => 0);
      $this->User->save($data);
      $this->redirect($this->referer());
      //$this->render("/layouts/debug");
    } else {
      throw new ForbiddenException();
      
    }
  }

/**
 * Edit user
 *
 * @param email, password
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function edit() {
    $this->authenticate_user();
    //get the id of the user to be edited
    $id = $this->request->params['pass'][0];
    //set the user id
    $this->User->id = $id;
    //check if a user with this id really exists
    if( $this->User->exists() ){
        if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
            //saveAssociated is important, this is to save associated model alse if data presented.
            $this->request->data['User']['id'] = $id;
            //$this->request->data['Profile']['user_id'] = $id;
            //var_dump($this->request->data); exit;
            if( $this->User->saveAssociated( $this->request->data ) ){
                //set to user's screen
                $this->Session->setFlash('User was edited.');
                //redirect to user's list
                $this->redirect(array('action' => 'index'));
            }else{
                $this->Session->setFlash('Unable to edit user. Please, try again.');
            }
        }else{
            //we will read the user data
            //so it will fill up our html form automatically
            //$this->request->data = $this->User->findById($id);
            $this->request->data = $this->User->read();
        }
        
    }else{
        //if not found, we will tell the user that user does not exist
        $this->Session->setFlash('The user you are trying to edit does not exist.');
        $this->redirect(array('action' => 'index'));
        //or, since it we are using php5, we can throw an exception
        //it looks like this
        //throw new NotFoundException('The user you are trying to edit does not exist.');
    }
  }
 /**
 * Delete user
 *
 * @param id
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function delete() {
    $id = $this->request->params['pass'][0];
    //the request must be a post request 
    //that's why we use postLink method on our view for deleting user
    if( $this->request->is('get') ){
        $this->Session->setFlash('Delete method is not allowed.');
        $this->redirect(array('action' => 'index'));
        
        //since we are using php5, we can also throw an exception like:
        //throw new MethodNotAllowedException();
    }else{
    
        if( !$id ) {
            $this->Session->setFlash('Invalid id for user');
            $this->redirect(array('action'=>'index'));
            
        }else{
            //delete user
            if( $this->User->delete( $id ) ){
                //set to screen
                $this->Session->setFlash('User was deleted.');
                //redirect to users's list
                $this->redirect(array('action'=>'index'));
                
            }else{  
                //if unable to delete
                $this->Session->setFlash('Unable to delete user.');
                $this->redirect(array('action' => 'index'));
            }
        }
    }
  }
/**
 * Sign up page
 * @author Jason Lu 
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function sign_up() {

  }


/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function show() {
    $id = $this->request->params['pass'][0];
    try {
      $user = $this->User->find('first', 
        array('conditions' => array(
          'User.id' => $id
        ))
      );
      $this->set('user', $user);
    } catch (NotFoundException $e) {
      throw $e;
    }
  }
}
