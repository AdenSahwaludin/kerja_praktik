<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

interface Props {
    title?: string;
    bodyClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Sneat',
    bodyClass: '',
});

onMounted(() => {
    // Set body class for Sneat theme
    if (props.bodyClass) {
        document.body.className = props.bodyClass;
    }

    // Load Sneat scripts dynamically if needed
    const loadScript = (src: string) => {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        document.head.appendChild(script);
    };

    // Load required scripts
    loadScript('/sneat/assets/vendor/libs/jquery/jquery.js');
    loadScript('/sneat/assets/vendor/libs/popper/popper.js');
    loadScript('/sneat/assets/vendor/js/bootstrap.js');
    loadScript('/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js');
    loadScript('/sneat/assets/vendor/js/menu.js');
    loadScript('/sneat/assets/js/main.js');
});
</script>

<template>
    <Head :title="title">
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/sneat/assets/img/favicon/favicon.ico" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
        <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet"
        />

        <!-- Icons -->
        <link rel="stylesheet" href="/sneat/assets/vendor/fonts/boxicons.css" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="/sneat/assets/vendor/css/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="/sneat/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="/sneat/assets/css/demo.css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

        <!-- Page CSS -->
        <link rel="stylesheet" href="/sneat/assets/vendor/css/pages/page-auth.css" />
    </Head>

    <slot />
</template>

<style>
html,
body {
    height: 100%;
    margin: 0;
    padding: 0;
}

.light-style.customizer-hide {
    background-color: #f7f7f9;
}
</style>
