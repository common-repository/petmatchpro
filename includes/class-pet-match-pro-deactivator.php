<?php
class Pet_Match_Pro_Deactivator {
	public static function deactivate() {
            /*
             * 1. Remove values in DB for options but not lic key
             */
            //delete_option('pet-match-pro-general-options');
	}
}