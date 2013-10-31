<?php
/**
 * Patient controller.
 *
 * This file will render views from views/patients/
 *
 * PHP 5
 *
 * Copyright (c) Wenjie Shi (wjshi@bu.edu) 
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Wenjie Shi(wjshi@bu.edu)
 * @link          http://github.com/bumetcs/cs673
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PatientsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
  public $uses = array();

  //function __construct() {
    //$this->assign('title', "TEST");
    //parent::__construct();
    //$this->set('title', "Title");
  //}

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function display() {
    $path = func_get_args();
    $this->set('title', "Title");

    $count = count($path);
    if (!$count) {
      return $this->redirect('/');
    }
    $page = $subpage = $title_for_layout = null;

    if (!empty($path[0])) {
      $page = $path[0];
    }
    if (!empty($path[1])) {
      $subpage = $path[1];
    }
    if (!empty($path[$count - 1])) {
      $title_for_layout = Inflector::humanize($path[$count - 1]);
    }
    $this->set(compact('page', 'subpage', 'title_for_layout'));

    try {
      $this->render(implode('/', $path));
    } catch (MissingViewException $e) {
      if (Configure::read('debug')) {
        throw $e;
      }
      throw new NotFoundException();
    }
  }

public function index(){
  $this->authenticate_user();
  $uid = (int)$this->current_user['id'];
  $patients = $this->Patient->find('all', 
    ["conditions" =>
      ["user_id" => $uid]
    ]);
  $this->set('patients',$patients);
  if ($this->request->is('post')){
    $id = $this->request->data('patient_id');
    $patient = $this->Patient->findById($id);
    $this->Session->write('patient_id', $id);
    $this->Session->write('patient', $patient);
    $this->redirect(array('action'=>'show'));
  }
}


/**
 * Create new patient
 *
 * @param firstname, lastname, dob, gender
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 * @version 2.0 by Jason now work fine with form helper
 *
 */
  public function add(){
    $this->authenticate_user();
    $uid = (int)$this->current_user['id'];
    $this->Session->delete('patient_id');
    if ($this->request->is('post')){
      $patient = $this->request->data;
      $patient['Patient']['user_id'] = $uid;
      $this->Patient->create();

      if($this->Patient->save($patient)){
        $patient = $this->Patient->read();
        $this->Session->write('patient_id', $patient['Patient']['id']);
        $this->Session->write('patient', $patient);
        $this->Session->setFlash('Patient was added. Please save the patient ID so you can search for the patient later');
        $this->redirect(array('action'=>'show'));
      }else{
        $this->Session->setFlash('Patient is not saved.');
      }
    } else {
      // Render view.
    }
  }

/**
 * Search a patient
 *
 * @param patientid, firstname, lastname, dob
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function search(){
    $this->Session->delete('patient_id');
    if($this->request->is('post')){
      // Please... team DO indent
      $patient_number = $this->request->data('patient_number');
      $patient_firstname = strtolower($this->request->data('Patient_FirstName'));
      $patient_lastname = strtolower($this->request->data('Patient_LastName'));
      $patient_dob = $this->request->data('patient_dob');
      $conditions = array("patient_number" => $patient_number, strtolower("patient_firstname") => $patient_firstname, strtolower("patient_lastname") => $patient_lastname, "dob" => $patient_dob);
      $patient = $this->Patient->find('first', array('conditions' => $conditions));
      $patient = $patient['Patient'];
      $res = new stdClass();
      $res->status = 0;
      $res->message = "Initialized";
      $res->data = null;
      if (!$patient) {
        $res->status = -1;
        $res->message = __d("search", "Search failed");
        $res->data = $patient;
      } else {
        $res->status = 1;
        $res->message = __d("search", "search succeed");
        $res->data = $patient;
        $id = $patient['id'];
        $this->Session->write('patient', $patient);
        $this->Session->write('patient_id', $id);
        $this->set('patient', $patient);
        $this->redirect(array('action'=>'show'));
      }
      $this->Session->setFlash('Patient not found, please enter information again or add a new patient.','flash_search_failure');
    }
  }

/**
 * edit patient
 *
 * @param firstname, lastname, dob, gender
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 * @version 2.0 by Jason now work fine with form helper
 *
 */
  public function edit(){
    $this->authenticate_user();
    $id = $this->Session->read('patient_id');
    $this->Patient->id = $id;
    if($this->Patient->exists()){
      if($this->request->is('post') || $this->request->is('put')){
        $data = $this->request->data;
        //save patient
        if($this->Patient->save($data)){
          $this->Session->setFlash('Patient was edited.');
          $this->redirect(array('action'=>'show'));
        } else {
          $this->Session->setFlash('Unable to edit patient. Please, try again.');
        }
      } else {
        // Patient exists and this is a get request => render the view, pass exsiting date.
        $this->request->data = $this->Patient->read();
      }
    } else {
      $this->Session->setFlash('The patient you are trying to edit does not exist.');
      $this->redirect(array('action' => 'show'));
    }
  }  

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function show($id = null){
    //this->authenticate_user();
    $id = $this->Session->read('patient_id');
    //cakephp way
    //$this->Session->delete('patient_id');

    // native php way.
    //start_session();
    //$_SESSION['patient_id'] = null;
    //unset($_SESSION['patient_id']);

    //view all patients
    //$patients = $this->Patient->find(all);
    //$this->set('patients', $patients);

    //view a patient
    //$patient_number = $this->request->params['pass'][0];
    try{
      $patient = $this->Patient->findById($id);
      $this->set('patient', $patient);
      $this->Session->write('patient', $patient);
      $this->Session->write('patient_id', $id);
    }catch(NotFoundException $e){
      throw $e;
    }
  }

 /**
 * Delete patient
 *
 * @param id
 * @return void
 * @throws NotFoundException When the view file could not be found
 *  or MissingViewException in debug mode.
 */
  public function delete(){
    //this->authenticate_user();
    $id = $this->Session->read('patient_id');
     $patient = $this->Patient->findById($id);

     if($this->request->is('get') ){
      $this->Session->setFlash('Delete method is not allowed.');
      $this->redirect(array('action' => 'show'));
    } else {
       if(!$id) {
        $this->Session->setFlash('Invalid id for patient');
        $this->redirect(array('action'=>'show'));
      }else{
        //delete patient
        if( $this->Patient->delete( $id ) ){
            //set to screen
            $this->Session->setFlash('Patient deleted.');
            //redirect to users's list
            $this->redirect(array('action'=>'search'));
        }else{  
             //if unable to delete
            $this->Session->setFlash('Unable to delete patient.');
            $this->redirect(array('action' => 'show'));
        }
      }
    }
  }
}