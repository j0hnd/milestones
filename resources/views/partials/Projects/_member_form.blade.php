<tr id="team-member-list-wrapper">
    <td colspan="3">
        <select class="toggle-select-member form-control">
            <option disabled selected>Select Team Member</option>
            @if (!is_null($member_list))
                @foreach ($member_list as $member)
                <option value="{{ $member->uid }}">{{ $member->name }}</option>
                @endforeach
            @endif
        </select>
    </td>
</tr>
