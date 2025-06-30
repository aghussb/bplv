<script setup>
import { useLayout } from './composables/layout';
import { computed } from 'vue';

const { layoutConfig } = useLayout();

const logoUrl = computed(() => {
    return `${window.origin}/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});
</script>

<template>
    <div class="py-4 px-4 mx-0 mt-8 lg:mx-8">
        <div class="grid justify-content-between">
            <div class="col-12 md:col-2" style="margin-top: -1.5rem">
                <div class="flex flex-wrap align-items-center justify-content-center md:justify-content-start md:mb-0 mb-3 cursor-pointer">
                    <img :src="logoUrl" alt="footer sections" width="50" height="50" class="mr-2" />
                    <h4 class="font-medium text-3xl text-900">SAKAI</h4>
                </div>
            </div>

            <div class="col-12 md:col-10 lg:col-7">
                <div class="grid text-center md:text-left">
                    <div class="col-12 md:col-3">
                        <h4 class="font-medium text-2xl line-height-3 mb-3 text-900">Company</h4>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">About Us</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">News</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Investor Relations</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Careers</a>
                        <a class="line-height-3 text-xl block cursor-pointer text-700">Media Kit</a>
                    </div>

                    <div class="col-12 md:col-3 mt-4 md:mt-0">
                        <h4 class="font-medium text-2xl line-height-3 mb-3 text-900">Resources</h4>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Get Started</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Learn</a>
                        <a class="line-height-3 text-xl block cursor-pointer text-700">Case Studies</a>
                    </div>

                    <div class="col-12 md:col-3 mt-4 md:mt-0">
                        <h4 class="font-medium text-2xl line-height-3 mb-3 text-900">Community</h4>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Discord</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Events<img src="/demo/images/landing/new-badge.svg" class="ml-2" /></a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">FAQ</a>
                        <a class="line-height-3 text-xl block cursor-pointer text-700">Blog</a>
                    </div>

                    <div class="col-12 md:col-3 mt-4 md:mt-0">
                        <h4 class="font-medium text-2xl line-height-3 mb-3 text-900">Legal</h4>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Brand Policy</a>
                        <a class="line-height-3 text-xl block cursor-pointer mb-2 text-700">Privacy Policy</a>
                        <a class="line-height-3 text-xl block cursor-pointer text-700">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped></style>
