<?php //-->

class Conversation extends Eloquent
{
    protected $table = 'conversations';
    protected $primaryKey = 'conversation_id';

    public static function groupChats()
    {
        $groupMember = User::find(Auth::user()->id)->groupMember;

        $chats = new StdClass();
        $counter = 0;
        foreach($groupMember as $member) {
            $group = Group::find($member->group_id);
            $ongoing = Conversation::where('group_id', '=', $group->group_id)
                ->where('status', '=', 'OPEN')
                ->first();
            if(!empty($ongoing)) {
                $chats->$counter = $group;
                $chats->$counter->conversation = $ongoing;
                // get messages
                $messages = ChatConversation::where('conversation_id', '=', $ongoing->conversation_id)
                    ->leftJoin('users', 'chat_conversations.user_id', '=', 'users.id')
                    ->get();
                $chats->$counter->messages = ($messages->isEmpty()) ?
                    null : $messages;
                $counter++;
            }
        }

        return $chats;
    }
}
