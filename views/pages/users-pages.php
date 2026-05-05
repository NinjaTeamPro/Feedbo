<?php
use Feedbo\Classes\Board;

defined( 'ABSPATH' ) || exit;

$board      = new Board();
$all_boards = $board->getBoards();
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Feedbo – Board Users', 'feedbo' ); ?></h1>

	<!-- Bulk action bar -->
	<div id="feedbo-bulk-bar" style="display:none">
		<span id="feedbo-bulk-bar-label"></span>
		<button id="feedbo-add-users-btn" class="button button-primary">
			<?php esc_html_e( 'Add Users', 'feedbo' ); ?>
		</button>
	</div>

	<!-- Boards table -->
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column">
					<input id="feedbo-select-all" type="checkbox" />
				</td>
				<th><?php esc_html_e( 'Board Name', 'feedbo' ); ?></th>
				<th><?php esc_html_e( 'URL', 'feedbo' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $all_boards ) ) : ?>
			<tr><td colspan="3"><?php esc_html_e( 'No boards found.', 'feedbo' ); ?></td></tr>
			<?php else : ?>
			<?php foreach ( $all_boards as $b ) : ?>
			<tr>
				<th class="check-column">
					<input class="feedbo-board-cb" type="checkbox"
						value="<?php echo esc_attr( $b['id'] ); ?>"
						data-board-name="<?php echo esc_attr( $b['name'] ); ?>" />
				</th>
				<td>
					<a href="#" class="feedbo-board-link"
						data-board-id="<?php echo esc_attr( $b['id'] ); ?>"
						data-board-name="<?php echo esc_attr( $b['name'] ); ?>">
						<?php echo esc_html( $b['name'] ); ?>
					</a>
				</td>
				<td><?php echo esc_html( $b['url'] ); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<!-- ── Bulk add modal ─────────────────────────────────────── -->
<div id="feedbo-modal" style="display:none" aria-modal="true" role="dialog">
	<div class="feedbo-backdrop"></div>
	<div class="feedbo-modal-box">
		<h2 id="feedbo-modal-title"></h2>

		<div id="feedbo-modal-form">
			<div class="feedbo-field">
				<label for="feedbo-emails">
					<?php esc_html_e( 'Email addresses', 'feedbo' ); ?>
					<span class="description"><?php esc_html_e( '— one per line', 'feedbo' ); ?></span>
				</label>
				<textarea id="feedbo-emails" rows="6"
					placeholder="user1@example.com&#10;user2@example.com"></textarea>
			</div>
			<div class="feedbo-field">
				<label for="feedbo-role"><?php esc_html_e( 'Role', 'feedbo' ); ?></label>
				<select id="feedbo-role">
					<option value="Owner"><?php esc_html_e( 'Owner', 'feedbo' ); ?></option>
					<option value="Admin"><?php esc_html_e( 'Admin', 'feedbo' ); ?></option>
					<option value="Moderator" selected><?php esc_html_e( 'Moderator', 'feedbo' ); ?></option>
					<option value="Member"><?php esc_html_e( 'Member', 'feedbo' ); ?></option>
				</select>
			</div>
		</div>

		<div id="feedbo-modal-results" style="display:none"></div>

		<div class="feedbo-modal-footer">
			<button id="feedbo-modal-cancel" class="button"><?php esc_html_e( 'Cancel', 'feedbo' ); ?></button>
			<button id="feedbo-modal-submit" class="button button-primary"><?php esc_html_e( 'Add Users', 'feedbo' ); ?></button>
		</div>
	</div>
</div>

<!-- ── Board detail modal ─────────────────────────────────── -->
<div id="feedbo-board-modal" style="display:none" aria-modal="true" role="dialog">
	<div class="feedbo-backdrop"></div>
	<div class="feedbo-modal-box feedbo-board-modal-box">
		<div class="feedbo-bm-header">
			<h2 id="feedbo-bm-title"></h2>
			<button id="feedbo-bm-close" aria-label="<?php esc_attr_e( 'Close', 'feedbo' ); ?>">&#x2715;</button>
		</div>

		<!-- Add user inline form -->
		<div class="feedbo-bm-add-form">
			<input type="email" id="feedbo-bm-email"
				placeholder="<?php esc_attr_e( 'email@example.com', 'feedbo' ); ?>" />
			<select id="feedbo-bm-role-select">
				<option value="Owner"><?php esc_html_e( 'Owner', 'feedbo' ); ?></option>
				<option value="Admin"><?php esc_html_e( 'Admin', 'feedbo' ); ?></option>
				<option value="Moderator" selected><?php esc_html_e( 'Moderator', 'feedbo' ); ?></option>
				<option value="Member"><?php esc_html_e( 'Member', 'feedbo' ); ?></option>
			</select>
			<button id="feedbo-bm-add-btn" class="button button-primary">
				<?php esc_html_e( 'Add User', 'feedbo' ); ?>
			</button>
			<span id="feedbo-bm-msg"></span>
		</div>

		<!-- Users table -->
		<div class="feedbo-bm-table-wrap">
			<table class="wp-list-table widefat fixed striped feedbo-bm-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Email', 'feedbo' ); ?></th>
						<th class="col-role"><?php esc_html_e( 'Role', 'feedbo' ); ?></th>
						<th class="col-status"><?php esc_html_e( 'Status', 'feedbo' ); ?></th>
						<th class="col-actions"><?php esc_html_e( 'Actions', 'feedbo' ); ?></th>
					</tr>
				</thead>
				<tbody id="feedbo-bm-tbody">
					<tr><td colspan="4"><?php esc_html_e( 'Loading…', 'feedbo' ); ?></td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<style>
/* ── Bulk bar ─────────────────────────────────────────────── */
#feedbo-bulk-bar {
	display:flex; align-items:center; gap:12px;
	margin:16px 0 12px; padding:10px 14px;
	background:#f0f6fc; border:1px solid #c3c4c7; border-radius:3px;
}

/* ── Shared modal shell ───────────────────────────────────── */
#feedbo-modal, #feedbo-board-modal {
	position:fixed; inset:0; z-index:100000;
	display:flex; align-items:center; justify-content:center;
}
.feedbo-backdrop { position:fixed; inset:0; background:rgba(0,0,0,.55); }
.feedbo-modal-box {
	position:relative; background:#fff; border-radius:6px;
	padding:28px 32px 24px; width:520px; max-width:96vw;
	box-shadow:0 12px 40px rgba(0,0,0,.22);
}

/* ── Board detail modal ───────────────────────────────────── */
.feedbo-board-modal-box {
	width:740px; max-width:96vw; padding:0; display:flex;
	flex-direction:column; max-height:88vh; overflow:hidden;
}
.feedbo-bm-header {
	display:flex; align-items:center; justify-content:space-between;
	padding:18px 24px; border-bottom:1px solid #dcdcde; flex-shrink:0;
}
.feedbo-bm-header h2 { margin:0; font-size:16px; }
#feedbo-bm-close {
	background:none; border:none; font-size:22px; line-height:1;
	cursor:pointer; color:#646970; padding:0 4px; border-radius:3px;
}
#feedbo-bm-close:hover { color:#1d2327; background:#f0f0f1; }

/* Add-user inline form */
.feedbo-bm-add-form {
	display:flex; align-items:center; gap:8px; flex-wrap:wrap;
	padding:12px 24px; border-bottom:1px solid #dcdcde;
	background:#f6f7f7; flex-shrink:0;
}
.feedbo-bm-add-form input[type="email"] { flex:1; min-width:200px; }
.feedbo-bm-add-form select { width:130px; }
#feedbo-bm-msg { font-size:12px; font-style:italic; color:#646970; flex-basis:100%; margin:0; }

/* Scrollable table area */
.feedbo-bm-table-wrap { flex:1; overflow-y:auto; }
.feedbo-bm-table { margin:0 !important; border:none !important; }
.feedbo-bm-table thead th { position:sticky; top:0; background:#f6f7f7; z-index:1; }

/* Column widths */
.feedbo-bm-table .col-email  { word-break:break-all; }
.feedbo-bm-table .col-role   { width:140px; }
.feedbo-bm-table .col-status { width:96px; }
.feedbo-bm-table .col-actions{ width:186px; white-space:nowrap; }
.feedbo-bm-table .cell-role select { width:115px; }

/* Action buttons */
.col-actions .button { margin-right:4px; }
.col-actions .btn-del { color:#a00 !important; border-color:#caa !important; }
.col-actions .btn-del:hover { background:#fdf; color:#700 !important; }

/* Role badges */
.feedbo-badge-role {
	display:inline-block; padding:2px 9px; border-radius:10px;
	font-size:11px; font-weight:600; letter-spacing:.3px;
}
.feedbo-role-owner     { background:#ede9f7; color:#6941b8; }
.feedbo-role-admin     { background:#fff0e6; color:#c45500; }
.feedbo-role-moderator { background:#e6f4fd; color:#0073aa; }
.feedbo-role-member    { background:#f0f0f1; color:#50575e; }

/* Status badges */
.feedbo-badge-status {
	display:inline-block; padding:2px 9px; border-radius:10px; font-size:11px;
}
.feedbo-status-accept  { background:#edfaef; color:#1e6b28; font-weight:600; }
.feedbo-status-pending { background:#fef8ee; color:#856404; }

/* ── Bulk add modal fields ─────────────────────────────────── */
.feedbo-field { margin-bottom:18px; }
.feedbo-field label { display:block; font-weight:600; margin-bottom:5px; }
.feedbo-field label .description { font-weight:400; }
.feedbo-field textarea, .feedbo-field select { width:100%; }
.feedbo-modal-footer {
	display:flex; justify-content:flex-end; gap:8px;
	margin-top:20px; padding-top:16px; border-top:1px solid #dcdcde;
}

/* Results list */
#feedbo-modal-results {
	max-height:200px; overflow-y:auto; margin-bottom:4px;
	padding:12px 14px; background:#f6f7f7;
	border-left:3px solid #c3c4c7; border-radius:2px;
}
.feedbo-result-item   { padding:3px 0; font-size:13px; }
.feedbo-result-added  { color:#1e6b28; }
.feedbo-result-exists { color:#856404; }
.feedbo-result-invalid, .feedbo-err { color:#8b1e1e; }
</style>
