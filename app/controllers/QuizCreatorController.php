<?php //-->

class QuizCreatorController extends BaseController
{    
    public function __construct() {
        $this->beforeFilter('are-you-a-teacher');
    }
    
    public function getIndex() {
        return View::make('quizcreator.index');
    }
}
