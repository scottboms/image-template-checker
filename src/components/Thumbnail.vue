<template>
	<k-field :label="label">
			<k-item
				:image="{ src: src }"
				:text="filename"
				:info="size"
			/>
	</k-field>
</template>

<script>
export default {
  props: {
		label: String,
    src: String,
    alt: String,
    filename: String,
		size: String
  }
};
</script>
