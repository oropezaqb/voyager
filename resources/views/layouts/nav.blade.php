<div class="col-md-2">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link {{ Request::path() === 'home' ? 'active' : ''}}" href="/home">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('journal_entries*') ? 'active' : ''}}" href="/journal_entries">Journal Entries</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('subsidiary_ledgers*') ? 'active' : ''}}" href="/subsidiary_ledgers">Subsidiary Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('accounts*') ? 'active' : ''}}" href="/accounts">Account Titles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('reports*') ? 'active' : ''}}" href="/reports">Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('report_line_items*') ? 'active' : ''}}" href="/report_line_items">Report Line Items</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('documents*') ? 'active' : ''}}" href="/documents">Documents</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('queries*') ? 'active' : ''}}" href="/queries">Queries</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('company_users*') ? 'active' : ''}}" href="/company_users">Company Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('roles*') ? 'active' : ''}}" href="/roles">Roles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('abilities*') ? 'active' : ''}}" href="/abilities">Abilities</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('current_company*') ? 'active' : ''}}" href="/current_company">Current Company</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('companies*') ? 'active' : ''}}" href="/companies">Companies</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('applications*') ? 'active' : ''}}" href="/applications">Applications</a>
        </li>
    </ul>
</div>
