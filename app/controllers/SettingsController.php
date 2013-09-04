<?php //-->

class SettingsController extends BaseController {

    public function getIndex() {
        return View::make('settings.index');
    }

    public function getPasswordPage() {
        return View::make('settings.password');
    }
}
