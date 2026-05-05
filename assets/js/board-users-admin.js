/* global feedboAdmin */
jQuery(document).ready(function ($) {
    var ajaxUrl = feedboAdmin.ajaxUrl;
    var nonce   = feedboAdmin.nonce;
    var ROLES   = ['Owner', 'Admin', 'Moderator', 'Member'];
    var RESULT_LABELS = { added: 'Added', exists: 'Already in board', invalid: 'Invalid email' };

    function esc(s) { return $('<div>').text(s || '').html(); }
    function post(action, data, done, fail) {
        data.action = action;
        data.nonce  = nonce;
        $.post(ajaxUrl, data).done(done).fail(fail || function () {});
    }

    /* ── Checkbox / bulk bar ──────────────────────────────── */

    $('#feedbo-select-all').on('change', function () {
        $('.feedbo-board-cb').prop('checked', this.checked);
        syncBulkBar();
    });

    $(document).on('change', '.feedbo-board-cb', function () {
        var total   = $('.feedbo-board-cb').length;
        var checked = $('.feedbo-board-cb:checked').length;
        $('#feedbo-select-all')
            .prop('indeterminate', checked > 0 && checked < total)
            .prop('checked', checked === total && total > 0);
        syncBulkBar();
    });

    function syncBulkBar() {
        var n = $('.feedbo-board-cb:checked').length;
        $('#feedbo-bulk-bar-label').text(n + ' board' + (n > 1 ? 's' : '') + ' selected');
        $('#feedbo-bulk-bar').toggle(n > 0);
    }

    /* ── Bulk add modal ───────────────────────────────────── */

    var bulkDone = false;

    $('#feedbo-add-users-btn').on('click', function () {
        var n = $('.feedbo-board-cb:checked').length;
        $('#feedbo-modal-title').text('Add Users to ' + n + ' board' + (n > 1 ? 's' : ''));
        $('#feedbo-emails').val('');
        $('#feedbo-role').val('Moderator');
        $('#feedbo-modal-results').hide().html('');
        $('#feedbo-modal-form').show();
        $('#feedbo-modal-submit').text('Add Users').prop('disabled', false);
        $('#feedbo-modal-cancel').text('Cancel').show();
        bulkDone = false;
        $('#feedbo-modal').show();
        $('#feedbo-emails').focus();
    });

    function closeBulkModal() { $('#feedbo-modal').hide(); }
    $('#feedbo-modal-cancel').on('click', closeBulkModal);
    $('#feedbo-modal .feedbo-backdrop').on('click', closeBulkModal);

    $('#feedbo-modal-submit').on('click', function () {
        if (bulkDone) { closeBulkModal(); return; }
        var emails = $('#feedbo-emails').val().trim();
        if (!emails) { alert('Enter at least one email.'); return; }

        var boardIds = $('.feedbo-board-cb:checked').map(function () { return $(this).val(); }).get();
        var $btn = $(this).prop('disabled', true).text('Adding…');

        post('feedbo_bulk_add_users', { board_ids: boardIds, emails: emails, role: $('#feedbo-role').val() },
            function (res) {
                $btn.prop('disabled', false);
                if (res.success) {
                    var html = res.data.results.map(function (r) {
                        return '<div class="feedbo-result-item feedbo-result-' + r.status + '">'
                            + '<strong>' + esc(r.board_name) + ':</strong> ' + esc(r.email)
                            + ' — ' + (RESULT_LABELS[r.status] || r.status) + '</div>';
                    }).join('');
                    $('#feedbo-modal-results').html(html).show();
                    $('#feedbo-modal-form').hide();
                    $btn.text('Close').prop('disabled', false);
                    $('#feedbo-modal-cancel').hide();
                    bulkDone = true;
                } else {
                    $('#feedbo-modal-results').html('<p class="feedbo-err">' + esc(res.data || 'Error') + '</p>').show();
                    $btn.text('Add Users');
                }
            },
            function () {
                $btn.text('Add Users').prop('disabled', false);
                $('#feedbo-modal-results').html('<p class="feedbo-err">Request failed.</p>').show();
            }
        );
    });

    /* ── Board detail modal (click board name) ────────────── */

    var activeBoardId = null;

    $(document).on('click', '.feedbo-board-link', function (e) {
        e.preventDefault();
        activeBoardId = $(this).data('board-id');
        $('#feedbo-bm-title').text($(this).data('board-name'));
        $('#feedbo-bm-email').val('');
        $('#feedbo-bm-msg').text('');
        loadBoardUsers();
        $('#feedbo-board-modal').show();
    });

    function closeBoardModal() { $('#feedbo-board-modal').hide(); }
    $('#feedbo-bm-close, #feedbo-board-modal .feedbo-backdrop').on('click', closeBoardModal);

    function loadBoardUsers() {
        $('#feedbo-bm-tbody').html('<tr><td colspan="4">Loading…</td></tr>');
        post('feedbo_get_board_users', { board_id: activeBoardId }, function (res) {
            if (res.success) { renderBoardUsers(res.data); }
            else { $('#feedbo-bm-tbody').html('<tr><td colspan="4">Failed to load.</td></tr>'); }
        });
    }

    function renderBoardUsers(users) {
        if (!users.length) {
            $('#feedbo-bm-tbody').html('<tr><td colspan="4">No users yet.</td></tr>');
            return;
        }
        $('#feedbo-bm-tbody').html(users.map(buildUserRow).join(''));
    }

    function buildUserRow(u) {
        var opts = ROLES.map(function (r) {
            return '<option' + (r === u.term_role ? ' selected' : '') + '>' + r + '</option>';
        }).join('');
        var roleCls  = 'feedbo-badge-role feedbo-role-' + esc(u.term_role).toLowerCase();
        var statCls  = 'feedbo-badge-status feedbo-status-' + esc(u.status);
        return '<tr data-uid="' + esc(u.ID) + '">'
            + '<td class="col-email">' + esc(u.user_email) + '</td>'
            + '<td class="cell-role col-role">'
            +   '<span class="' + roleCls + '">' + esc(u.term_role) + '</span>'
            +   '<select style="display:none">' + opts + '</select>'
            + '</td>'
            + '<td class="col-status"><span class="' + statCls + '">' + esc(u.status) + '</span></td>'
            + '<td class="col-actions">'
            +   '<button class="button button-small btn-edit">Edit</button> '
            +   '<button class="button button-small button-primary btn-save" style="display:none">Save</button>'
            +   '<button class="button button-small btn-cancel-edit" style="display:none">Cancel</button> '
            +   '<button class="button button-small btn-del">Delete</button>'
            + '</td>'
            + '</tr>';
    }

    $(document).on('click', '#feedbo-bm-tbody .btn-edit', function (e) {
        e.preventDefault();
        var $tr = $(this).closest('tr');
        $tr.find('.cell-role span').hide();
        $tr.find('.cell-role select').show();
        $tr.find('.btn-edit, .btn-del').hide();
        $tr.find('.btn-save, .btn-cancel-edit').show();
    });

    $(document).on('click', '#feedbo-bm-tbody .btn-cancel-edit', function (e) {
        e.preventDefault();
        var $tr = $(this).closest('tr');
        $tr.find('.cell-role select').hide();
        $tr.find('.cell-role span').show();
        $tr.find('.btn-save, .btn-cancel-edit').hide();
        $tr.find('.btn-edit, .btn-del').show();
    });

    $(document).on('click', '#feedbo-bm-tbody .btn-save', function (e) {
        e.preventDefault();
        var $tr  = $(this).closest('tr');
        var role = $tr.find('.cell-role select').val();
        post('feedbo_update_user_role', { user_id: $tr.data('uid'), role: role }, function (res) {
            if (!res.success) return;
            $tr.find('.cell-role span').text(role).show();
            $tr.find('.cell-role select').hide();
            $tr.find('.btn-save, .btn-cancel-edit').hide();
            $tr.find('.btn-edit, .btn-del').show();
        });
    });

    $(document).on('click', '#feedbo-bm-tbody .btn-del', function (e) {
        e.preventDefault();
        if (!confirm('Remove this user from the board?')) return;
        var $tr = $(this).closest('tr');
        post('feedbo_delete_board_user', { user_id: $tr.data('uid') }, function (res) {
            if (res.success) { $tr.fadeOut(200, function () { $(this).remove(); }); }
        });
    });

    $('#feedbo-bm-add-btn').on('click', function () {
        var email = $('#feedbo-bm-email').val().trim();
        if (!email) { $('#feedbo-bm-msg').text('Enter an email.'); return; }
        var $btn = $(this).prop('disabled', true);
        post('feedbo_add_single_user',
            { board_id: activeBoardId, email: email, role: $('#feedbo-bm-role-select').val() },
            function (res) {
                $btn.prop('disabled', false);
                if (res.success) {
                    renderBoardUsers(res.data);
                    $('#feedbo-bm-email').val('');
                    $('#feedbo-bm-msg').text('User added.');
                } else {
                    $('#feedbo-bm-msg').text(res.data || 'Error.');
                }
            },
            function () { $btn.prop('disabled', false); $('#feedbo-bm-msg').text('Request failed.'); }
        );
    });

    /* ── Global Escape ────────────────────────────────────── */
    $(document).on('keydown', function (e) {
        if (e.key !== 'Escape') return;
        closeBulkModal();
        closeBoardModal();
    });
});
