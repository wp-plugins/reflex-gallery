<?php
if (!class_exists("ResponsiveGallery")) {
	class ResponsiveGallery {
		public function __construct($galleryId, $db) {
			$this->id = $galleryId;
			$this->db = $db;
		}
		
		public function render() {
			$imageResults = $this->getImages();
			$gallerySetting = $this->getGallery();
			$galleryPanels = "";
			
			foreach($imageResults as $image)
			{
				$title = ($image->title == null) ? "" : $image->title;
				$description = ($image->description == null || $image->description == "") ? "" : "<p class=\"flex-caption\">".$image->description."</p>";				
				$galleryPanels .= "<li><img src=\"".$image->imagePath."\" alt=\"".$title."\" />".$description."</li>";
			}
			
			$gallery = "<div class=\"flexslider\"><ul class=\"slides\" data-width=\"".$gallerySetting->thumbwidth."\" data-height=\"".$gallerySetting->thumbheight."\">".$galleryPanels."</ul></div>";
			
			return $gallery;
		}
		
		public function getImages() {						
			$images = $this->db->getImagesByGalleryId($this->id);			
			return $images;
		}
		
		public function getGallery() {
			$gallery = $this->db->getGalleryById($this->id);
			return $gallery;
		}
	}
}
?>