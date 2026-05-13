@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Push Notifications</h5>

            <a href="{{ route('admin.push_notifications.create') }}"
               class="btn btn-primary">
                Add Notification
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Send Type</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Scheduled At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($notifications as $key => $notification)
                            <tr>
                                <td>{{ $notifications->firstItem() + $key }}</td>

                                <td>{{ $notification->title }}</td>

                                <td>{{ Str::limit($notification->message, 50) }}</td>

                                <td>{{ ucfirst($notification->send_type) }}</td>

                                <td>{{ ucfirst($notification->user_type) }}</td>

                                <td>
                                    @if($notification->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $notification->scheduled_at ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No Notifications Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>

        </div>
    </div>

</div>
@endsection