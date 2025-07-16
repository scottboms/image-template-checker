import ImageList from "./components/ImageList.vue";
import Thumbnail from "./components/Thumbnail.vue";

panel.plugin("scottboms/template-checker", {
	components: {
		"k-image-template-checker": ImageList,
	},
	fields: {
		thumbnail: Thumbnail
	}
});
