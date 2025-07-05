<template>
  <k-panel-inside>

    <k-view class="k-images-without-template-view">
      <k-header>Images</k-header>
      <div class="k-table">
        <table v-if="images.length">
        <thead>
          <tr>
            <th data-mobile="true">Filename</th>
            <th>Parent Page</th>
            <th data-mobile="true" data-align="right" style="width: 12rem"> </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="image in images" :key="image.id">
            <td data-mobile="true">{{ image.filename }}</td>
            <td>{{ image.parent }}</td>
            <td data-mobile="true" data-align="right">
              <k-bar align="end">
                <k-view-button
                  variant="filled"
                  size="xs"
                  icon="preview"
                  theme="blue"
                  text="View"
                  :link="image.url"
                />
                <k-view-button
                  v-if="image.parentPanelUrl"
                  variant="filled"
                  size="xs"
                  icon="edit"
                  text="Edit..."
                  :link="image.parentPanelUrl"
                />
              </k-bar>
            </td>
          </tr>
        </tbody>
        </table>

        <k-grid v-else style="--columns: 1; gap: 0.5rem">
          <k-empty text='No images missing blueprint templates found' icon="image" />
        </k-grid>
      </div>
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
  mounted() {
    // Debugging
    // console.log('Image props:', this.images);
  }
};
</script> 