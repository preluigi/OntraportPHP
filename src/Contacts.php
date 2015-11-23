<?php
namespace Ontraport;

class Contacts extends Objects {
	protected static $object_id = 0;

	public function add_tag ( $id, $tag_id ) {
		return protected add_tag_to_object ( $id, $tag_id );
	}

	public function remove_tag ( $id, $tag_id ) {
		return protected remove_tag_to_object ( $id, $tag_id );
	}
}
?>
