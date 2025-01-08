<x-mail::message>
# Quest Messages

This is a message from {{ $message->full_name}}.

Name: {{ $message->full_name}}
<br>
Email: {{ $message->email}}
<br>
Phone Number: {{ $message->phone_number?? 'N/A' }}
<br>

<x-mail::panel>
{{ $message->message}}
</x-mail::panel>
<br>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
