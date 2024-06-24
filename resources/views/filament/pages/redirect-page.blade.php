<x-filament::page>
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Generate Link ID</th>
                                    <th>Geo</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($redirects as $redirect)
                                    <tr>
                                        <td>{{ $redirect->id }}</td>
                                        <td>{{ $redirect->generate_link_id }}</td>
                                        <td>{{ $redirect->geo }}</td>
                                        <td>{{ $redirect->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
