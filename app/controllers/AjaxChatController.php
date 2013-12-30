<?php //-->

class AjaxChatController extends BaseController
{
    public function chatDetails()
    {
        $groupId = Input::get('group_id');
        $conversationId = Input::get('conversation_id');

        // get messages if there are
        $chats = ChatConversation::where('conversation_id', '=', $conversationId)
            ->get();

        if(empty($chats)) {
            return Response::json(array('chats' => false));
        }

        return Response::json(array('chats' => true));
    }

    public function getMessages()
    {
        $conversationId = Input::get('conversation_id');
        $lastId = Input::get('last_id');

        if(isset($lastId)) {
            $chats = ChatConversation::where('conversation_id', '=', $conversationId)
                ->where('chat_conversation_id', '>', $lastId)
                ->leftJoin('users', 'chat_conversations.user_id', '=', 'users.id')
                ->get();
        }

        if(!isset($lastId)) {
            $chats = ChatConversation::where('conversation_id', '=', $conversationId)
                ->leftJoin('users', 'chat_conversations.user_id', '=', 'users.id')
                ->get();
        }

        return View::make('ajax.chat.messages')
            ->with('chats', $chats);
    }

    public function sendMessage()
    {
        $conversationId = Input::get('conversation_id');
        $message = Input::get('message');

        // save
        $chat = new ChatConversation;
        $chat->conversation_id = $conversationId;
        $chat->user_id = Auth::user()->id;
        $chat->message = $message;
        $chat->save();

        return Response::json(array(
            'last_conversation_id' => $chat->chat_conversation_id));
    }
}
