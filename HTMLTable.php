<?php
/**
 * class HTML Table
 *
 * This class will output an html table
 */
class HTMLTable {

	/**
	 * Create a default table id in case none is provided
	 */
	private static $_table_instance_id = 0;

	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct( $args = array() ) {

		self::$_table_instance_id++;
		$table_id = self::$_table_instance_id;
		$defaults = array(
			'table_id' => $table_id,
			'table_class' => 'table',
			'display_footer' => false,
			'no_rows_message' => 'Nothing Found',
			'display_table' => true,
			'limit_rows' => -1
		);
		$args = wp_parse_args( $args,$defaults );
		extract($args);
		$this->table_args = $args;

	}

	/**
	 * Setup the values for the <th>s in the table
	 *
	 * @param array
	 */
	public function setHeaders( $headers = array() ) {

		$this->table_headers = $headers;

	}

	/**
	 * Setup the content to be displayed by the table
	 *
	 * @param array
	 */
	public function setTableContent( $content = array() ) {

		$this->table_rows = $content;

	}

	/**
	 * Display the html table
	 */
	public function displayTable() {

		if ( !$this->table_args['display_table'] ) {
			ob_start();
		}
		$column_count = count( $this->table_headers );
		?>
		<table id="table-<?php echo $this->table_args['table_id']; ?>" class="<?php echo $this->table_args['table_class']; ?>">
			<thead>
				<tr>
					<?php
					foreach ( $this->table_headers as $k => $v ) :
						if ( !is_array( $v ) ) :
							?><th><?php echo $v; ?></th><?php
						else :
							?><th width="<?php echo $v[0]; ?>"><?php echo $k; ?></th><?php
						endif;
					endforeach;
					?>
				</tr>
			</thead>
			<tbody>
				<?php if ( !$this->table_rows ) : ?>
					<tr>
						<td colspan="<?php echo $column_count; ?>"><?php echo $this->table_args['no_rows_message']; ?></td>
					</tr>
				<?php else : ?>
					<?php $i = 1; ?>
					<?php foreach ( $this->table_rows as $row ) : ?>
					<tr<?php echo ( isset( $row['row_style'] ) ) ? ' class="' . $row['row_style'] . '"' : ''; ?><?php echo ( isset( $row['row_id'] ) ) ? ' id="' . $row['row_id'] . '"' : ''; ?>>
						<?php foreach ( range( 1, $column_count ) as $r ) : ?>
							<?php $col = 'col' . $r; ?>
							<td><?php echo $row[$col]; ?></td>
						<?php endforeach; ?>
					</tr>
					<?php
					if ( $this->table_args['limit_rows'] === -1 ) continue;
					if ( $i == $this->table_args['limit_rows'] ) break;
					$i++;
					?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<?php if ( $this->table_args['display_footer'] ) : ?>
			<tfoot>
				<tr>
					<?php
					foreach ( $this->table_headers as $k => $v ) :
						if ( !is_array( $v ) ) :
							?><th><?php echo $v; ?></th><?php
						else :
							?><th width="<?php echo $v[0]; ?>"><?php echo $k; ?></th><?php
						endif;
					endforeach;
					?>
				</tr>
			</tfoot>
			<?php endif; ?>
		</table>
		<?php
		if ( !$this->table_args['display_table'] ) {
			$table = ob_get_contents();
			ob_end_clean();
			return $table;
		}

	}

}
?>
