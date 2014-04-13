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
			'display_footer' => true,
			'no_rows_message' => 'Nothing Found'
		);
		$args = $this->parseArgs( $args,$defaults );
		extract($args);	
		$this->table_args = $args;
			
	}
	
	/**
	 * This will take arguments and defaults, compare them and then return the revised array
	 *
	 * @param array Overrides
	 * @param array Defaults
	 */
	public function parseArgs( $args, $defaults = array() ) {
	
		if ( is_object( $args ) ) :
			$r = get_object_vars( $args );
		elseif ( is_array( $args ) ) :
			$r =& $args;
		else :
	        $this->parseString($args,$r);
		endif;  
		if ( is_array( $defaults ) ) :
	        return array_merge( $defaults, $r );
		endif;
	        
		return $r;
	
	}

   /**
     * used by parseArgs to help parse out the args and defaults
     *
     * @param string
     * @param array
     * @return array
     */
    public function parseString( $string, &$array ) {
    
    	parse_str( $string, $array );
    
        if ( get_magic_quotes_gpc() ) :
        	$array = stripslashes_deep($array);
        endif;
            
		return $array;
    
    }
        	
	/**
	 * Setup the values for the <th>s in the table
	 *
	 * @param array
	 */
	public function setHeaders( $headers ) {
	
		$this->table_headers = $headers;
	
	}
	
	/**
	 * Setup the content to be displayed by the table
	 *
	 * @param array
	 */
	public function setTableContent( $content ) {
	
		$this->table_rows = $content;
	
	}
	
	/**
	 * Display the html table
	 */
	public function displayTable() {

		$column_count = count( $this->table_headers );
		?>
		<table id="table-<?php echo $this->table_args['table_id']; ?>" class="<?php echo $this->table_args['table_class']; ?>">
			<thead>
				<tr>
					<?php foreach ( $this->table_headers as $h ) : ?>
						<th><?php echo $h; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php if ( !$this->table_rows ) : ?>
					<tr>
						<td colspan="<?php echo $column_count; ?>"><?php echo $this->table_args['no_rows_message']; ?></td>
					</tr>				
				<?php else : ?>
					<?php foreach ( $this->table_rows as $row ) : ?>
					<tr<?php echo ( isset( $row['row_style'] ) ) ? ' class="' . $row['row_style'] . '"' : ''; ?><?php echo ( isset( $row['row_id'] ) ) ? ' id="' . $row['row_id'] . '"' : ''; ?>>
					<?php foreach ( range( 1, $column_count ) as $r ) : ?>
							<?php $col = 'col' . $r; ?>
							<td><?php echo $row[$col]; ?></td>
						<?php endforeach; ?>
					</tr>	
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<?php if ( $this->table_args['display_footer'] ) : ?>
			<tfoot>
				<tr>
					<?php foreach ( $this->table_headers as $h ) : ?>
						<th><?php echo $h; ?></th>
					<?php endforeach; ?>
				</tr>
			</tfoot>
			<?php endif; ?>
		</table>
		<?php
		
	}

}
?>
