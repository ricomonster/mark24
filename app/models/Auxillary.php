<?php //-->
class Auxillary
{
    public static function groupChats()
    {
        $chats = Conversation::groupChats();
        return View::make('ajax.chat.chatbox')
            ->with('chats', $chats);
    }
}