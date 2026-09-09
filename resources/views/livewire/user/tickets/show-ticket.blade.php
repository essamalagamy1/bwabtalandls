<div>
    <x-header title="{{ $ticket->subject }}" subtitle="تذكرة رقم #{{ $ticket->id }}">
        <x-slot:actions>
            <x-button icon="o-arrow-right" class="btn-ghost btn-sm" link="{{ route('user.tickets.index') }}"
                label="عودة للتذاكر" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="المحادثة">
                <div class="space-y-6 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300">
                    @foreach ($ticket->replies as $reply)
                        @php
                            $isMe = $reply->user_id === auth()->id();
                        @endphp
                        <div class="chat {{ $isMe ? 'chat-end' : 'chat-start' }}">
                            <div class="chat-image avatar">
                                <div class="w-10 rounded-full">
                                    <img src="{{ $reply->user->getFirstMediaUrl('image') ?: asset('avatar.png') }}" />
                                </div>
                            </div>
                            <div class="chat-header mb-1">
                                {{ $isMe ? 'أنت' : $reply->user->name }}
                                <time
                                    class="text-xs opacity-50 mx-2">{{ $reply->created_at->format('M d, H:i') }}</time>
                            </div>
                            <div class="chat-bubble {{ $isMe ? 'chat-bubble-primary' : 'chat-bubble-neutral' }}">
                                {{ $reply->message }}
                                @if ($reply->hasMedia('attachments'))
                                    <div class="mt-3">
                                        <a href="{{ $reply->getFirstMediaUrl('attachments') }}" target="_blank"
                                            title="عرض الصورة بحجمها الأصلي">
                                            <img src="{{ $reply->getFirstMediaUrl('attachments') }}"
                                                class="w-32 h-32 object-cover rounded-lg border border-base-300 shadow-sm transition-transform hover:scale-105" />
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($ticket->status !== 'closed')
                    <div class="mt-8 border-t pt-4">
                        <x-form wire:submit="sendReply">
                            <x-textarea wire:model="replyMessage" placeholder="اكتب ردك هنا..." rows="3"
                                required />
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-4">
                                <div class="w-full sm:w-1/2">
                                    <x-file wire:model="attachment" accept="image/*" label="إرفاق صورة (اختياري)" />
                                </div>
                                <div class="w-full sm:w-auto text-end">
                                    <x-button type="submit" class="btn-primary w-full sm:w-auto"
                                        icon="o-paper-airplane" label="إرسال" spinner="sendReply" />
                                </div>
                            </div>
                        </x-form>
                    </div>
                @else
                    <div class="mt-8 alert alert-warning">
                        هذه التذكرة مغلقة. لا يمكنك إضافة ردود جديدة.
                    </div>
                @endif
            </x-card>
        </div>

        <div class="lg:col-span-1">
            <x-card title="تفاصيل التذكرة">
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">النوع</div>
                        @php
                            $typeNames = ['problem' => 'مشكلة', 'suggestion' => 'اقتراح', 'inquiry' => 'استفسار'];
                        @endphp
                        <div class="font-bold">{{ $typeNames[$ticket->type] ?? $ticket->type }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">الحالة</div>
                        @php
                            $statusNames = ['open' => 'مفتوحة', 'in_progress' => 'قيد المراجعة', 'closed' => 'مغلقة'];
                        @endphp
                        <div class="font-bold">{{ $statusNames[$ticket->status] ?? $ticket->status }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">تاريخ الإنشاء</div>
                        <div class="font-bold">{{ $ticket->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    @if ($ticket->assignedAdmin)
                        <div>
                            <div class="text-sm text-gray-500">المشرف المسؤول</div>
                            <div class="font-bold flex items-center gap-2 mt-1">
                                <x-avatar :image="$ticket->assignedAdmin->getFirstMediaUrl('image')" class="w-6 h-6" />
                                {{ $ticket->assignedAdmin->name }}
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>
