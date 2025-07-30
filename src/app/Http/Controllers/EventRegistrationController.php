<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    /**
     * イベントに参加申込
     */
    public function store(Event $event): RedirectResponse
    {
        //ログイン必須
        if(!auth()->check()){
            return redirect()->route('login')
                ->with('error', 'ログインが必要です。');
        }

        $user = auth()->user();

        //申込可能かチェック
        if(!$event->canRegister()){
            return redirect()->route('events.show', $event)
                ->with('error', 'このイベントは申込できません。');
        }

        //既に申込済みかチェック
        if($event->isRegisteredBy($user)){
            return redirect()->route('events.show', $event)
                ->with('error', '既にこのイベントに申込済みです。');
        }

        //申込を作成
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
            'registered_at' => now()
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'イベントに申込しました。');

    }

    /**
     * 参加申込をキャンセル
     */
    public function destroy(Event $event): RedirectResponse
    {
        $user = auth()->user();

        //申込データを取得
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->first();

        if(!$registration){
            return redirect()->route('events.show', $event)
                ->with('error', '申込が見つかりません。');
        }

        //申込をキャンセル
        $registration->cancel();

        return redirect()->route('events.show', $event)
            ->with('success', 'イベントの申込をキャンセルしました。');
    }

    /**
     * イベントの参加者一覧表示(作成者のみ)
     */
    public function participants(Event $event): View
    {
        if($event->created_by !== auth()->id()){
            abort(403, 'このイベントの参加者を表示する権限がありません。');
        }

        //参加申込データを取得
        $registrations = $event->registrations()
            ->with('user')
            ->where('status', 'registered')
            ->orderBy('registered_at', 'asc')
            ->get();

        return view('events.participants', compact('event', 'registrations'));
    }
}
