<?php //-->

class AjaxQuizCreatorController extends BaseController {

    public function getCheckActiveQuiz()
    {
        $existingQuiz = Quiz::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'ACTIVE')
            ->first();

        if(!empty($existingQuiz)) {
            $questionCount = QuestionList::where('quiz_id', '=', $existingQuiz->quiz_id)
                ->count();
            // get the first question
            $question = QuestionList::where('quiz_id', '=', $existingQuiz->quiz_id)
                ->join('questions', 'question_lists.question_id', '=', 'questions.question_id')
                ->first();

            return Response::json(array(
                'quiz_id'           => $existingQuiz->quiz_id,
                'quiz_title'        => $existingQuiz->title,
                'quiz_time_limit'   => $existingQuiz->time_limit,
                'question_list_id'  => $question->question_list_id,
                'question_id'       => $question->question_id,
                'question_type'     => $question->question_type,
                'question_point'    => $question->question_point,
                'question_count'    => $questionCount,
                'active'            => true));
        }

        return Response::json(array('active' => false));
    }

    public function postCreateQuiz()
    {
        $questionType   = Input::get('question_type');
        $quizTitle      = Input::get('quiz_title');
        $quizTimeLimit  = Input::get('quiz_time_limit');

        // create quiz
        $newQuiz                = new Quiz;
        $newQuiz->user_id       = Auth::user()->id;
        $newQuiz->title         = $quizTitle;
        $newQuiz->time_limit    = $quizTimeLimit;
        $newQuiz->total_score   = 1;
        $newQuiz->save();

        // create question
        $newQuestion                = new Question;
        $newQuestion->question_type = $questionType;
        $newQuestion->save();

        // add to question list
        $addToList              = new QuestionList;
        $addToList->quiz_id     = $newQuiz->quiz_id;
        $addToList->question_id = $newQuestion->question_id;
        $addToList->save();

        // create an answer field for the question type
        switch($questionType) {
            // multiple choice
            case 'MULTIPLE_CHOICE' :
                // create 2 choices. one is the correct answer
                // while the other one is just a choice
                $correctOption              = new MultipleChoice;
                $correctOption->question_id = $newQuestion->question_id;
                $correctOption->is_answer   = 'TRUE';
                $correctOption->save();

                // create another option
                $anotherOption              = new MultipleChoice;
                $anotherOption->question_id = $newQuestion->question_id;
                $anotherOption->save();

                break;
            // true or false
            case 'TRUE_FALSE' :
                // by default, the answer is true
                $addAnswer              = new TrueFalse;
                $addAnswer->question_id = $newQuestion->question_id;
                $addAnswer->save();

                break;
            default :
                break;
        }

        // return awesome data
        $return = array(
            'quiz_id'           => $newQuiz->quiz_id,
            'question_id'       => $newQuestion->question_id,
            'question_type'     => $questionType,
            'question_list_id'  => $addToList->question_list_id);

        return Response::json($return);
    }

    public function postUpdateQuiz()
    {
        $quizId     = Input::get('quiz_id');
        $quizTitle  = Input::get('title');

        // update the quiz
        $quiz = Quiz::find($quizId);
        $quiz->title = $quizTitle;
        $quiz->save();

        // send a response
        $return['error'] = false;

        return Response::json($return);
    }

    public function getQuestion()
    {
        $quizId         = Input::get('quiz_id');
        $questionListId = Input::get('question_list_id');

        // let's search for the question details
        $question = QuestionList::where('question_list_id', '=', $questionListId)
            ->where('quiz_id', '=', $quizId)
            ->join('questions', 'question_lists.question_id', '=', 'questions.question_id')
            ->first();

        // determine the question type
        switch($question->question_type) {
            case 'MULTIPLE_CHOICE' :
                $response = MultipleChoice::where('question_id', '=', $question->question_id)
                    ->get();
                break;

            case 'TRUE_FALSE' :
                $response = TrueFalse::where('question_id', '=', $question->question_id)
                    ->first();
                break;
            default :
                $response = null;
                break;
        }

        // return Response::json($question);
        return View::make('ajax.quizcreator.question')
            ->with('question', $question)
            ->with('response', $response);
    }

    public function getQuestions()
    {
        $quizId = Input::get('quiz_id');

        $questions = QuestionList::where('quiz_id', '=', $quizId)
            ->join('questions', 'question_lists.question_id', '=', 'questions.question_id')
            ->get();

        return View::make('ajax.quizcreator.questions')
            ->with('questions', $questions);
    }

    public function getQuestionLists()
    {
        $quizId = Input::get('quiz_id');

        $questionLists = QuestionList::where('quiz_id', '=', $quizId)
            ->get();

        return View::make('ajax.quizcreator.questionlists')
            ->with('lists', $questionLists);
    }

    public function postUpdateQuestion()
    {
        $questionId         = Input::get('question_id');
        $multipleChoiceId   = Input::get('multiple_choice_id');
        $trueFalseId        = Input::get('true_false_id');

        $questionType       = Input::get('question_type');
        $questionText       = Input::get('question_text');
        $questionPoint      = Input::get('question_point');

        $choiceText         = Input::get('choice_text');
        $trueFalseAnswer    = Input::get('answer');

        $question = Question::find($questionId);

        if(isset($questionType) && !empty($questionType)) {
            // delete first the previous
            switch($question->question_type) {
                case 'MULTIPLE_CHOICE' :
                    MultipleChoice::where('question_id', '=', $question->question_id)
                        ->delete();
                    break;
                case 'TRUE_FALSE' :
                    TrueFalse::where('question_id', '=', $question->question_id)
                        ->delete();
                    break;
                default :
                    break;
            }

            // create the choices
            switch($questionType) {
                case 'MULTIPLE_CHOICE' :
                    // create 2 choices. one is the correct answer
                    // while the other one is just a choice
                    $correctOption              = new MultipleChoice;
                    $correctOption->question_id = $question->question_id;
                    $correctOption->is_answer   = 'TRUE';
                    $correctOption->save();

                    // create another option
                    $anotherOption              = new MultipleChoice;
                    $anotherOption->question_id = $question->question_id;
                    $anotherOption->save();

                    $response = MultipleChoice::where('question_id', '=', $question->question_id)
                        ->get();
                    break;
                case 'TRUE_FALSE' :
                    $addAnswer              = new TrueFalse;
                    $addAnswer->question_id = $question->question_id;
                    $addAnswer->save();

                    $response = TrueFalse::where('question_id', '=', $question->question_id)
                        ->first();
                    break;
                default :
                    $response = null;
                    break;
            }

            // update the question type of the question
            $question->question_type = $questionType;
            $question->save();

            // get the responses
            return View::make('ajax.quizcreator.responses')
                ->with('question', $question)
                ->with('response', $response);
        } else if(isset($questionText) && !empty($questionText)) {
            $question->question = $questionText;
            $question->save();

            $return['error'] = false;

            return Response::json($return);
        } else if(isset($choiceText) && !empty($choiceText)) {
            $multipleChoice                 = MultipleChoice::find($multipleChoiceId);
            $multipleChoice->choice_text    = $choiceText;
            $multipleChoice->save();

            $return['error'] = false;

            return Response::json($return);
        } else if(isset($multipleChoiceId) && isset($questionId)) {
            // reset first all choices to not an answer
            $choices = MultipleChoice::where('question_id', '=', $questionId)
                ->update(array('is_answer' => 'FALSE'));

            // set the choice to correct one
            $correctChoice = MultipleChoice::find($multipleChoiceId);
            $correctChoice->is_answer = 'TRUE';
            $correctChoice->save();

            $return['error'] = false;

            return Response::json($return);
        } else if(isset($trueFalseId) && isset($trueFalseAnswer)) {
            // find the true false answer
            $answer = TrueFalse::find($trueFalseId);
            $answer->answer = $trueFalseAnswer;
            // update
            $answer->save();

            // return response
            $return['error'] = false;

            return Response::json($return);
        } else if(isset($questionPoint) && !empty($questionPoint)) {
            // update question point
            $question = Question::find($questionId);
            $question->question_point = $questionPoint;
            $question->save();

            // return response
            $return['error'] = false;

            return Response::json($return);
        } else if(isset($multipleChoiceId) && !empty($multipleChoiceId)) {
            // let's delete the choice
            $choice = MultipleChoice::find($multipleChoiceId);
            $choice->delete();

            $return['error'] = false;

            return Response::json($return);
        }
    }

    public function postAddQuestion()
    {
        $quizId = Input::get('quiz_id');
        $questionType = Input::get('question_type');

        // create question
        $newQuestion                = new Question;
        $newQuestion->question_type = $questionType;
        $newQuestion->save();

        // add to question list
        $addToList              = new QuestionList;
        $addToList->quiz_id     = $quizId;
        $addToList->question_id = $newQuestion->question_id;
        $addToList->save();

        // create an answer field for the question type
        switch($questionType) {
            // multiple choice
            case 'MULTIPLE_CHOICE' :
                // create 2 choices. one is the correct answer
                // while the other one is just a choice
                $correctOption              = new MultipleChoice;
                $correctOption->question_id = $newQuestion->question_id;
                $correctOption->is_answer   = 'TRUE';
                $correctOption->save();

                // create another option
                $anotherOption              = new MultipleChoice;
                $anotherOption->question_id = $newQuestion->question_id;
                $anotherOption->save();

                break;
            // true or false
            case 'TRUE_FALSE' :
                // by default, the answer is true
                $addAnswer              = new TrueFalse;
                $addAnswer->question_id = $newQuestion->question_id;
                $addAnswer->save();

                break;
            default :
                break;
        }

        $return = array(
            'question_id'       => $newQuestion->question_id,
            'question_list_id'  => $addToList->question_list_id);

        return Response::json($return);
    }

    public function postAddResponse()
    {
        $questionId = Input::get('question_id');

        // add a response
        $response = new MultipleChoice;
        $response->question_id = $questionId;
        $response->save();

        // get details
        $responseDetails = MultipleChoice::find($response->multiple_choice_id);

        // return the view response
        return View::make('ajax.quizcreator.addresponse')
            ->with('question_id', $questionId)
            ->with('r', $responseDetails);
    }

    public function postRemoveQuestion()
    {
        $quizId         = Input::get('quiz_id');
        $questionId     = Input::get('question_id');
        $questionListId = Input::get('question_list_id');

        // delete the question from the list
        QuestionList::where('quiz_id', '=', $quizId)
            ->where('question_id', '=', $questionId)
            ->where('question_list_id', '=', $questionListId)
            ->delete();

        // return a response
        return Response::json(array('error' => false));
    }

    public function postSubmitQuiz()
    {
        $quizId     = Input::get('quiz_id');
        $totalScore = Input::get('total_score');

        // update the quiz
        $quiz = Quiz::find($quizId);
        $quiz->status = 'READY';
        $quiz->total_score = $totalScore;
        $quiz->save();

        // set up session with quiz details
        Session::flash('quiz_details', $quiz);

        return Response::json(array(
            'lz'    => Request::root().'/home',
            'error' => false));
    }
}
