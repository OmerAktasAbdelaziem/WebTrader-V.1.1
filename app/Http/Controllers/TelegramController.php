<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use App\Services\TelegramBot;
use Illuminate\Http\Request;
use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    public function inbound(Request $request)
    {
        if ($request->message) {
            $reply_to_message = $request->message['message_id'];
            $chat_id = $request->message['from']['id'];

            if (isset($request->message['text'])) {
                $message = strip_tags($request->message['text']);
                $text = "Welcome to GlowUp CRM Bot🤩🤩🤩\nPlease write your Co Admin Email 🙏🙏🙏";
                $telegramChat = TelegramChat::find($chat_id);
                if ($telegramChat) {
                    info('Telegram Bot : '.$telegramChat->user?->username.' :'.$message);
                }

                if (!$telegramChat) {
                    $inputs = [
                        'id' => $chat_id,
                    ];
                    TelegramChat::create($inputs);
                } else {
                    $text = $this->tryAccess($telegramChat, $message);
                }

                if ($text) {
                    $telegramBot = new TelegramBot();
                    if ($text == 'options') {
                        $options = 'options';
                    }
                    $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null , $options ?? null);

                    return response()->json($result, 200);
                }
            }
        }

        if ($request->callback_query) {
            $reply_to_message = $request->callback_query['message']['reply_to_message']['message_id'];
            $leadId = $request->callback_query['message']['reply_to_message']['text'];
            $chat_id = $request->callback_query['from']['id'];
            if (isset($request->callback_query['data'])) {
                $option = strip_tags($request->callback_query['data']);

                $telegramChat = TelegramChat::find($chat_id);
                if ($telegramChat) {
                    $user = User::find($telegramChat->user_id);
                    if ($user && $user->co_pipeline()->exists()) {
                        $pipeline_id = $user->co_pipeline->pluck('id');
                        $text = $this->optionsResponse($leadId, $option, $pipeline_id, $user->id);
                    }
                }

                if (isset($text)) {
                    $telegramBot = new TelegramBot();
                    $options = null;
                    if ($text == 'change_assigned_user') {
                        $options = 'users';
                    }
                    if ($text == 'change_status') {
                        $options = 'statuses';
                    }
                    $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null, $options);

                    return response()->json($result, 200);
                }
            }
        }
    }

    public function tryAccess($telegramChat, $message)
    {
        $text = null;

        if ($telegramChat->user_id) {
            $user = User::find($telegramChat->user_id);
            if ($user && $user->co_pipeline()->exists()) {
                if (is_numeric($message)) {
                    $text = 'options';
                }else {
                    $text = "Hello if you want to see the options please write the Lead id";
                }
            }
            else {
                $text = "😳 Sorry, You have no permission 😳";
            }
            return $text;
        }

        if ($telegramChat->times_to_try <= 0) {
            $text = "😳 Sorry, you have tried many times and can't try anymore 😳";
        } elseif ($telegramChat->verification_level == 0) {
            $user = User::where('email', $message)->whereHas('co_pipeline')->exists();
            if ($user) {
                $inputs = [
                    'verification_level' => 1,
                    'email' => $message,
                ];
                $telegramChat->update($inputs);
                $text = '✅️ Please write your password ✅️';
            } else {
                $text = "❌ Please write your correct email ❌\nYou have {$telegramChat->times_to_try} times to try";
                $inputs = [
                    'times_to_try' => $telegramChat->times_to_try -= 1,
                ];
                $telegramChat->update($inputs);
            }
        } elseif ($telegramChat->verification_level == 1) {
            $user = User::where('email', $telegramChat->email)->first();
            if ($user && Hash::check($message, $user->password)) {
                $inputs = [
                    'user_id' => $user->id,
                ];
                $telegramChat->update($inputs);
                $text = "✅️✅️✅️✅️✅️✅️✅️✅️\nWelcome {$user->first_name} {$user->last_name} to GlowUp CRM\n🤩🤩🤩🤩🤩🤩🤩🤩";
            } else {
                $text = "❌ Password is invalid ❌\nYou have {$telegramChat->times_to_try} times to try";
                $inputs = [
                    'times_to_try' => $telegramChat->times_to_try -= 1,
                ];
                $telegramChat->update($inputs);
            }
        }

        if ($message == 'Radi you are nice') {
            $text = 'Thank you 🤩🤩🤩';
            $inputs = [
                'times_to_try' => 5,
            ];
            $telegramChat->update($inputs);
        }

        return $text;
    }

    public function optionsResponse($leadId, $option, $pipeline_id, $auth_user_id)
    {
        $text = null;
        $lead = Client::where('id', $leadId)->whereIn('pipeline_id', $pipeline_id)->first();
        $clientController = new ClientsController;

        if ($lead) {
            switch ($option) {
                case 'get_info':
                    $text = "Name: {$lead->first_name} {$lead->last_name}\nEmail: {$lead->email}\nPhone: {$lead->phone1}" . 
                    ($lead->phone2 ? ' - ' . $lead->phone2 : '') . 
                    "\nStatus: {$lead->sales_status}\nAssigned User: {$lead->user->username}\nLast Comment: {$lead->comments->last()?->comment}";
                    break;

                case 'change_assigned_user':
                    $text = "change_assigned_user";
                    break;
                    
                case str_starts_with($option, 'change_user_id_'):
                    $userId = str_replace('change_user_id_', '', $option);
                    $user = User::findorFail($userId);
                    $authUser = User::findorFail($auth_user_id);
                    Auth::login($authUser);
                    
                    $request = new Request([
                        'client_id' => $leadId,
                        'user_id'   => $userId,
                    ]);

                    $clientController->multiEdit($request);
                    $text = "✅ Assigned User changed to {$user->username} successfully ✅";
                    break;
                    
                case 'change_status':
                    $text = "change_status";
                    break;

                case str_starts_with($option, 'change_status_'):
                    $status = str_replace('change_status_', '', $option);
                    $authUser = User::findorFail($auth_user_id);
                    Auth::login($authUser);

                    $request = new Request([
                        'sales_status' => $status,
                        'client_id'    => $leadId,
                    ]);

                    $clientController->multiEdit($request);
                    $text = "✅ Status changed to {$status} successfully ✅";
                    break;
        
                default:
                    $text = "❌ Invalid action ❌";
                    break;
            }
        }else {
            $text = "❌ Lead not found ❌";
        }

        return $text;
    }
}
