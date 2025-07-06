<template>
	<k-panel-inside>
		<k-view class="k-images-without-template-view">
			<k-header>Images</k-header>
			<k-section label="Images Without a File Blueprint">
				<k-items
					v-if="images.length"
					:items="items"
					:layout="table"
					:sortable="true"
				/>
				<k-grid v-else style="--columns: 1; gap: 0.5rem">
					<k-empty text='No images found' icon="image" />
				</k-grid>
			</k-section>
		</k-view>
	</k-panel-inside>
</template>

<script>
export default {
	name: 'ImageList',
	props: {
		images: {
			type: Array,
			default: () => []
		}
	},
	computed: {
		items() {
			return this.images.map((image, index) => ({
				text: image.filename,
				info: image.parent,
				link: image.fileUrl,
				image: {
					src: image.thumbUrl,
					cover: true,
					back: 'pattern',
				},
				options: [
					image.parentPanelUrl && {
						icon: 'open',
						text: 'Edit...',
						link: image.parentPanelUrl
					},
					{
						icon: 'window',
						text: 'Preview',
						link: image.url
					}
				].filter(Boolean)
			}));
		}
	},
	mounted() {
		// Debugging
		console.log('Image props:', this.images);
	}
};
</script> 