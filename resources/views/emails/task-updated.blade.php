<x-mail::message>
    Task Update Notification

    The task **{{ $task->title }}** has been updated.
    Description: {{ $task->description }}
    Due Date: {{ $task->due_date }}

<x-mail::button :url={{ $url }}>
 View
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
