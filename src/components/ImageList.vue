<template>
	<div>
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
	</div>
</template>

<script>
export default {
	name: 'ImageList',
	props: {
		images: {
			type: Array,
			default: () => []
		},
		availableTemplates: {
			type: Array,
			default: () => []
		}
	},
	data() {
		return {
			selectedImage: null,
			templateDrawer: false, // DEBUG
		};
	},
  watch: {
    templateDrawer(open) {
      console.log('templateDrawer changed:', open);
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
					image.fileUrl && {
						icon: 'open',
						text: 'Edit...',
						link: image.fileUrl,
					},
					{
						icon: 'window',
						text: 'Preview',
						link: image.url
					},
					{
						icon: 'settings',
						text: 'Assign Template…',
						click: () => this.openTemplateSelector(image)
					}
				].filter(Boolean)
			}));
		}
	},
	methods: {
		// this is now globally accessible within the component
	  handleTemplateSubmit(formData) {
	    const { key, template } = formData;

	this.$api.post('checker/assign', { key, template })
	    .then(() => {
	      panel.notification.success(`Assigned template: ${template}`);
	      //panel.view.reload(); // optional, reload view
	    })
	    .catch(() => {
	      panel.notification.error('Failed to assign template');
	    });
	  },

		openTemplateSelector(image) {
			console.log('🗃️ Opening drawer with file ID:', image.id);

			this.$panel.drawer.open({
				component: 'k-form-drawer',
				props: {
					title: 'Assign File Template',
					icon: 'image',
					fields: {
						'filename': {
							'label': 'File',
							'type': 'info',
							'text': `${image.id}`,
							'theme': 'empty',
							'icon': 'file',
							'width': '1/1',
						},
						'template': {
							'label': 'Template',
							'type': 'select',
							'required': true,
							'options': this.availableTemplates,
							'value': image.templateInContent || null,
							'width': '1/1',
							'help': 'Choose an available file template'
						},
						'alt': {
							'label': 'Alt Text',
							'type': 'textarea',
							'buttons': false,
							'size': 'medium',
							'value': `${image.alt}`,
							'width': '1.1'
						}
					},
					value: {
						key: image.id,
						template: image.templateInContent || null,
						alt: image.alt,
						//contentFilename: image.contentFilename
					},
				},
				on: {
					submit: this.handleTemplateSubmit.bind(this)
				}
			});
		},
		closeDrawer() {
			this.$panel.drawer.close();
		}
	},
	mounted() {
		// debugging
		console.log('Image props:', this.images);
		console.log('🎨 availableTemplates:', this.availableTemplates);
	}
};
</script>
