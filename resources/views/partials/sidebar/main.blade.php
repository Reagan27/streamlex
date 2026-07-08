<nav class="col-md-2 sidebar">
    <div class="user-box text-center pt-5 pb-3">
        <!-- <div class="user-img">
            <img src="{{ optional(auth()->user())->present() ? auth()->user()->present()->avatar : asset('default-avatar.png') }}"
                 width="90"
                 height="90"
                 alt="user-img"
                 class="rounded-circle img-thumbnail img-responsive">
        </div> -->
        <!-- <h5 class="mt-3">
            <a href="{{ route('profile') }}">{{ optional(auth()->user())->present() ? auth()->user()->present()->nameOrEmail : '' }}</a>
        </h5> -->
    

        <!-- <ul class="list-inline mb-2">
            <li class="list-inline-item">
                <a href="{{ route('profile') }}" title="@lang('My Profile')">
                    <i class="fas fa-cog"></i>
                </a>
            </li>

            <li class="list-inline-item">
                <a href="{{ route('auth.logout') }}" class="text-custom" title="@lang('Logout')">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul> -->
    </div>

    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            @foreach (\Vanguard\Plugins\Vanguard::availablePlugins() as $plugin)
                @include('partials.sidebar.items', ['item' => $plugin->sidebar()])
                @if (isset($plugin) && method_exists($plugin, 'sidebar') && $plugin->sidebar() && $plugin->sidebar()->getTitle() === 'Back to Office Reports')
                    @include('partials.sidebar.items', ['item' => (object) [
                        'authorize' => fn($user) => true,
                        'getActivePath' => fn() => 'data-collection*',
                        'getHref' => fn() => route('data-collection.index'),
                        'isDropdown' => fn() => false,
                        'getIcon' => fn() => 'bi bi-ui-checks-grid',
                        'getTitle' => fn() => 'Data Collection',
                    ]])
                @endif
                @if (isset($plugin) && method_exists($plugin, 'sidebar') && $plugin->sidebar() && $plugin->sidebar()->getTitle() === 'Field Reports')
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#field-activities-menu" role="button" aria-expanded="false" aria-controls="field-activities-menu">
                            <i class="bi bi-calendar-check"></i>
                            <span>Task Management</span>
                        </a>
                        <ul class="collapse list-unstyled ms-4" id="field-activities-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('field-activities.fam') }}">
                                    <i class="fas fa-list me-2"></i>Digital Logsheet
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('regions.index') }}">
                                    <i class="fas fa-map me-2"></i>Region
                                </a>
                            </li> -->
                        </ul>
                    </li>
                @endif
            @endforeach
            @include('partials.sidebar.items', ['item' => (object) [
                'authorize' => fn($user) => true,
                'getActivePath' => fn() => 'hr-compliance*',
                'getHref' => fn() => '#hr-compliance',
                'isDropdown' => fn() => true,
                'getIcon' => fn() => 'bi bi-people',
                'getTitle' => fn() => 'HR & Compliance',
                'children' => fn() => [
                    (object) [
                        'authorize' => fn($user) => true,
                        'getActivePath' => fn() => 'nda*',
                        'getHref' => fn() => route('nda.show'),
                        'isDropdown' => fn() => false,
                        'getIcon' => fn() => 'bi bi-file-earmark-lock',
                        'getTitle' => fn() => 'Employee NDA',
                    ],
                    (object) [
                        'authorize' => fn($user) => true,
                        'getActivePath' => fn() => 'policy*',
                        'getHref' => fn() => route('policy.acknowledgement'),
                        'isDropdown' => fn() => false,
                        'getIcon' => fn() => 'bi bi-journal-check',
                        'getTitle' => fn() => 'HR Policy',
                    ],
                    (object) [
                        'authorize' => fn($user) => true,
                        'getActivePath' => fn() => 'document-acknowledgements*',
                        'getHref' => fn() => '#document-acknowledgements',
                        'isDropdown' => fn() => true,
                        'getIcon' => fn() => 'bi bi-file-earmark-check',
                        'getTitle' => fn() => 'Document Acknowledgements',
                        'children' => fn() => [
                            (object) [
                                'authorize' => fn($user) => true,
                                'getActivePath' => fn() => 'document-acknowledgements/assignments*',
                                'getHref' => fn() => route('document_acknowledgements.assignments.index'),
                                'isDropdown' => fn() => false,
                                'getIcon' => fn() => 'bi bi-list-ul',
                                'getTitle' => fn() => 'My Documents',
                            ],
                            (object) [
                                'authorize' => fn($user) => $user->hasPermission('compliance.view'),
                                'getActivePath' => fn() => 'compliance/document-acknowledgements*',
                                'getHref' => fn() => route('compliance.document_acknowledgements.index'),
                                'isDropdown' => fn() => false,
                                'getIcon' => fn() => 'bi bi-gear',
                                'getTitle' => fn() => 'Manage Documents',
                            ],
                            (object) [
                                'authorize' => fn($user) => $user->hasPermission('compliance.create'),
                                'getActivePath' => fn() => 'compliance/document-acknowledgements/create*',
                                'getHref' => fn() => route('compliance.document_acknowledgements.create'),
                                'isDropdown' => fn() => false,
                                'getIcon' => fn() => 'bi bi-plus-circle',
                                'getTitle' => fn() => 'Create Document',
                            ],
                        ],
                    ],
                ]
            ]])
            <!-- Coach Requisition Sidebar Link -->
            @include('partials.sidebar.items', ['item' => (object) [
                'authorize' => fn($user) => true,
                'getActivePath' => fn() => 'coach-requisitions*',
                'getHref' => fn() => url('coach-requisitions'),
                'isDropdown' => fn() => false,
                'getIcon' => fn() => 'bi bi-person-lines-fill',
                'getTitle' => fn() => 'Requisition',
            ]])
            <!-- Meetings Sidebar Link -->
            @include('partials.sidebar.items', ['item' => (object) [
                'authorize' => fn($user) => true,
                'getActivePath' => fn() => 'meetings*',
                'getHref' => fn() => url('meetings'),
                'isDropdown' => fn() => false,
                'getIcon' => fn() => 'bi bi-calendar-event',
                'getTitle' => fn() => 'Meetings',
            ]])
        </ul>
    </div>
</nav>


