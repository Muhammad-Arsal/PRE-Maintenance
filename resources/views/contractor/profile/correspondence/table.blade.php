<table class="table mb-0 custom-table" id="manageTransactions">
    <thead>
        <tr>
            <th style="width: 5%" ></th>
            <th style="width: 6%;" >Type</th>
            <th>Item</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody class="borderless">
        @foreach ($all as $d)
            @if(isset($d->name))
            <tr>
                <td><input class="check" value="{{ $d->id."+".'folder' }}" style="cursor: pointer;" type="checkbox" name="check[]"></td>
                <td><i style="color: #fdb900;" class="la la-folder"></i></td>
                <td><a style="color: black; text-decoration: none;" href="{{ route('contractor.contractors.correspondence.child', ['id' => $contractor->id, 'parent_id' => $d['id'] ]) }}">{{ $d['name'] }}</a></td>
                <td style="color: black">{{ isset($d['created_at']) ? date('d/m/Y H:i', strtotime($d['created_at'])) : null }}</td>
            </tr>
            @else
            <tr>
                @if($d->is_text)
                <td></td>
                <td>
                    <i class="la la-comment" ></i>
                </td>
                <td>
                    <span>{{ $d->text }}</span>
                </td>
              @else
                <td><input value="{{ $d->id."+"."file" }}" class="check" style="cursor: pointer;" type="checkbox" name="check[]"></td>
                <td><i class="la la-file-o"></i></td>
                <td style="color: black;" >{{ $d->file_name }}</td>
              @endif  
                <td style="color: black;" >{{ isset($d->created_at) ? date('d/m/Y H:i', strtotime($d->created_at)) : null }}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>