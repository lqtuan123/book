<nav class="side-nav">

    <ul>
        <li>
            <a href="{{ route('admin.home') }}" class="side-menu side-menu{{ $active_menu == 'dashboard' ? '--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="home"></i></div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>

        
        <!-- Group Sidebar Menu -->
        <li>
            <a href="javascript:;"
                class="side-menu {{ $active_menu == 'group_list' || $active_menu == 'group_add' || $active_menu == 'group_member' || $active_menu == 'group_role' || $active_menu == 'group_type' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="align-center"></i></div>
                <div class="side-menu__title">
                    Nhóm
                    <div class="side-menu__sub-icon transform"><i data-lucide="chevron-down"></i></div>
                </div>
            </a>
            <ul
                class="{{ $active_menu == 'group_list' || $active_menu == 'group_add' || $active_menu == 'group_member' || $active_menu == 'group_role' || $active_menu == 'group_type' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.group.index') }}"
                        class="side-menu {{ $active_menu == 'group_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="compass"></i></div>
                        <div class="side-menu__title">Danh sách nhóm</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.group.create') }}"
                        class="side-menu {{ $active_menu == 'group_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="plus"></i></div>
                        <div class="side-menu__title">Thêm nhóm</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.grouprole.index') }}"
                        class="side-menu {{ $active_menu == 'group_role' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="briefcase"></i></div>
                        <div class="side-menu__title">Vai trò nhóm</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.grouptype.index') }}"
                        class="side-menu {{ $active_menu == 'group_type' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="layers"></i></div>
                        <div class="side-menu__title">Loại nhóm</div>
                    </a>
                </li>
            </ul>
        </li>

       
        <li>
            <a href="javascript:;" class="side-menu  class="side-menu
                {{ $active_menu == 'ugroup_add' || $active_menu == 'ugroup_list' || $active_menu == 'ctm_add' || $active_menu == 'ctm_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                <div class="side-menu__title">
                    Người dùng
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul
                class="{{ $active_menu == 'ugroup_add' || $active_menu == 'ugroup_list' || $active_menu == 'ctm_add' || $active_menu == 'ctm_list' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.user.index') }}"
                        class="side-menu {{ $active_menu == 'ctm_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                        <div class="side-menu__title">Danh sách người dùng</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.user.create') }}"
                        class="side-menu {{ $active_menu == 'ctm_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm người dùng</div>
                    </a>
                </li>
                
            </ul>
        </li>

        <!-- Points Management Menu -->
        <li>
            <a href="javascript:;" class="side-menu 
                {{ $active_menu == 'point_rules' || $active_menu == 'point_reports' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="award"></i> </div>
                <div class="side-menu__title">
                    Quản lý điểm
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ $active_menu == 'point_rules' || $active_menu == 'point_reports' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.points.index') }}"
                        class="side-menu {{ $active_menu == 'point_rules' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Quy tắc điểm</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.points.reports') }}"
                        class="side-menu {{ $active_menu == 'point_reports' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="bar-chart"></i> </div>
                        <div class="side-menu__title">Báo cáo điểm</div>
                    </a>
                </li>
            </ul>
        </li>
        
        <!-- Resource  -->
        <li>
            <a href="javascript:;"
                class="side-menu {{ $active_menu == 'resource_list' || $active_menu == 'resource_add' || $active_menu == 'resourcetype_list' || $active_menu == 'resourcelinktype_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="file"></i> </div>
                <div class="side-menu__title">
                    Tài nguyên
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul
                class="{{ $active_menu == 'resource_list' || $active_menu == 'resource_add' || $active_menu == 'resourcetype_list' || $active_menu == 'resourcelinktype_list' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.resources.index') }}"
                        class="side-menu {{ $active_menu == 'resource_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title">Danh sách tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.resources.create') }}"
                        class="side-menu {{ $active_menu == 'resource_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.resource-types.index') }}"
                        class="side-menu {{ $active_menu == 'resourcetype_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="folder"></i> </div>
                        <div class="side-menu__title"> Loại tài nguyên </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.resource-link-types.index') }}"
                        class="side-menu {{ $active_menu == 'resourcelinktype_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="link"></i> </div>
                        <div class="side-menu__title"> Loại liên kết tài nguyên </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Book -->
        <li>
            <a href="javascript:;"
                class="side-menu {{ $active_menu == 'book_list' || $active_menu == 'book_add' || $active_menu == 'booktype_list' || $active_menu == 'bookpoint_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="book-open"></i> </div>
                <div class="side-menu__title">
                    Sách
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul
                class="{{ $active_menu == 'book_list' || $active_menu == 'book_add' || $active_menu == 'booktype_list' || $active_menu == 'bookpoint_list' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.books.index') }}"
                        class="side-menu {{ $active_menu == 'book_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.books.create') }}"
                        class="side-menu {{ $active_menu == 'book_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.booktypes.index') }}"
                        class="side-menu {{ $active_menu == 'booktype_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title">Danh mục sách</div>
                    </a>
                </li>
                
            </ul>
        </li>

        <!-- Tuongtac -->
        <li>
            <a href="javascript:;"
                class="side-menu {{ $active_menu == 'tblog_list' || $active_menu == 'tblog_add' || $active_menu == 'tblog_edit' || $active_menu == 'tblog_view' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="edit-3"></i> </div>
                <div class="side-menu__title">
                    Tương tác
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ $active_menu == 'tblog_list' || $active_menu == 'tblog_add' || $active_menu == 'tblog_edit' || $active_menu == 'tblog_view' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.tblogs.index') }}"
                        class="side-menu {{ $active_menu == 'tblog_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Bài viết</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tblogs.create') }}"
                        class="side-menu {{ $active_menu == 'tblog_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm bài viết</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- setting menu -->
        <li>
            <a href="javascript:;.html"
                class="side-menu side-menu{{ $active_menu == 'cmdfunction_list' || $active_menu == 'cmdfunction_add' || $active_menu == 'role_list' || $active_menu == 'role_add' || $active_menu == 'kiot' || $active_menu == 'setting_list' || $active_menu == 'log_list' || $active_menu == 'banner_add' || $active_menu == 'banner_list' ? '--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                <div class="side-menu__title">
                    Cài đặt
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul
                class="{{ $active_menu == 'cmdfunction_list' || $active_menu == 'cmdfunction_add' || $active_menu == 'role_list' || $active_menu == 'role_add' || $active_menu == 'kiot' || $active_menu == 'setting_list' || $active_menu == 'banner_add' || $active_menu == 'banner_list' ? 'side-menu__sub-open' : '' }}">

                <li>
                    <a href="{{ route('admin.role.index', 1) }}"
                        class="side-menu {{ $active_menu == 'role_list' || $active_menu == 'role_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="octagon"></i> </div>
                        <div class="side-menu__title"> Roles</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.cmdfunction.index', 1) }}"
                        class="side-menu {{ $active_menu == 'cmdfunction_list' || $active_menu == 'cmdfunction_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="moon"></i> </div>
                        <div class="side-menu__title"> Chức năng</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.edit', 1) }}"
                        class="side-menu {{ $active_menu == 'setting_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="key"></i> </div>
                        <div class="side-menu__title"> Thông tin công ty</div>
                    </a>
                </li>


            </ul>
        </li>
      
    </ul>
</nav>
