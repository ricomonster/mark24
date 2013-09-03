<?php

class ProfileController extends BaseController {

    public function showIndex() {
        return View::make('profile.index');
    }

}
