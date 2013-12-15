<?php //-->

class AjaxChatController extends BaseController
{
    public function chatDetails()
    {
        $currentDate = date('Y-m-d');
        $groupId = Input::get('group_id');
        // check if there's an existing conversation for this day
        $conversation = Conversation::where('group_id', '=', $groupId)
            ->first();

        if(empty($conversation)) {
            // create conversation
            $newConversation = new Conversation;
            $newConversation->group_id = $groupId;
            $newConversation->save();

            return Response::json(array(
                'conversation' => false,
                'conversation_id' => $newConversation->conversation_id));
        } else if(date('Y-m-d', strtotime($conversation->created_at)) == $currentDate) {
            // there's an existing conversation
            // fetch conversations
            return Response::json(array(
                'conversation' => true,
                'conversation_id' => $conversation->conversation_id));
        } else if(date('Y-m-d', strtotime($conversation->created_at)) != $currentDate) {
            // no existing conversation for the current date
            // create conversation
            $newConversation = new Conversation;
            $newConversation->group_id = $groupId;
            $newConversation->save();

            return Response::json(array(
                'conversation' => false,
                'conversation_id' => $newConversation->conversation_id));
        }
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
