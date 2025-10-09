<script setup lang="ts">
import {
  Card,
  CardContent,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge'
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index, show } from '@/actions/App/Http/Controllers/FileController';
import { type BreadcrumbItem } from '@/types';
import { Check, Circle, Dot, Loader2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import files from '@/routes/files';

const props = defineProps<{
    file: any;
}>();

const fileStatus = ref(props?.file?.data?.status);

const progressPercentage = ref(0);
progressPercentage.value = props.file.data.progress;


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Files',
        href: index().url,
    },
    {
        title: props?.file?.data?.name,
        href: index().url,
    },
];

onMounted(() => {
    window.Echo.private(`file-status.${props.file.data.id}`)
        .listen('FilesStatusUpdated', (e) => {
            console.log("working");

            progressPercentage.value = e.progress;

            if(e.progress > 100) {
                progressPercentage.value = 100;
            }

            fileStatus.value = e.status;
        });
});


const steps = [
    {
        step: 1,
        title: "Chunking",
        description: "Splitting file into chunks",
    },
    {
        step: 2,
        title: "Embeddings",
        description: "Generating embeddings",
    },
    {
        step: 3,
        title: "Storage",
        description: "Saving embeddings to vector storage",
    },
]

</script>


<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <Card>
                    <CardContent>
                       <div class="flex gap-x-1 gap-y-2">
                           <div class="flex w-1/5">
                               <img src="/pdf-image.png" alt="Image of Pdf" class="w-20 h-20">
                           </div>
                          <div class="flex flex-col w-4/5">
                            <div class="flex flex-col gap-2 space-y-1 text-sm">
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Name: </span>
                                    <span class="font-medium w-4/5">{{  file?.data?.name }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Size: </span>
                                    <span class="font-medium w-4/5">{{  file?.data?.size }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Author: </span>
                                    <span class="font-medium w-4/5">{{ file?.data?.author ?? "No Author in this PDF" }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Pages: </span>
                                    <span class="font-medium w-4/5">{{ file?.data?.pages }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Uploaded: </span>
                                    <span class="font-medium w-4/5">{{ file?.data?.created_at }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Type: </span>
                                    <span class="font-medium w-4/5">{{ file?.data?.type }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-400 text-sm w-1/5">Status: </span>
                                    <div class="flex items-center">
                                        <span v-if="fileStatus === 'completed'" class="font-medium w-4/5"><Badge variant="outline" class="bg-green-500">Completed</Badge></span>
                                        <span v-if="fileStatus === 'failed'" class="font-medium w-4/5"><Badge variant="outline" class="bg-red-500">Failed</Badge></span>
                                        <span v-if="fileStatus === 'processing'" class="font-medium w-4/5"><Badge variant="outline" class="bg-yellow-500"><Loader2 class="w-10 h-10 animate-spin"></Loader2>Processing</Badge></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </CardContent>
                </Card>
                <div>
                    <div class="max-w-3xl mx-auto p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                            <h3 class="text-lg font-semibold">Processing status</h3>
                            <p class="text-sm text-gray-500">Background pipeline progress and logs</p>
                            </div>
                        </div>

                        <!-- Progress bar -->
                        <div class="relative h-3 rounded-full bg-gray-200 overflow-hidden">
                            <!-- filled portion -->
                            <div class="absolute left-0 top-0 h-full bg-blue-500 rounded-full transition-all duration-500 ease-out" :class="progressPercentage === 100 ? 'bg-green-500' : 'bg-blue-500'" :style="{ width: progressPercentage + '%' }"></div>
                        </div>
                        
                        <div class="mt-2 mb-6">
                            <span class="text-sm text-gray-200 font-medium">{{ progressPercentage }}/100</span>
                        </div>
                        

                        <!-- Details / timeline -->
                        <div class="space-y-3 shadow-sm divide-y">
                            <Card v-for="step in steps" :key="step.step">
                                <CardContent>
                                    <div class="flex gap-4 items-start">
                                        <!-- <div v-if="step.status.value === 'completed'" class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-medium">✓</div> -->
                                        <!-- <div v-if="step.status.value === 'active'" class="w-8 h-8 rounded-full border-2 border-blue-600 bg-white text-blue-600 flex items-center justify-center text-sm font-medium animate-pulse">●</div> -->
                                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">{{ step.step }}</div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                            <div>
                                                <!-- :class="step.status.value === 'completed' ? 'text-green-600' : (step.status.value === 'active' ? 'text-blue-600 animate-pulse' : 'text-gray-600')" -->
                                                <div 
                                                    class="text-sm font-semibold text-gray-600"
                                                >{{ step.title }}</div>
                                                <div class="text-xs text-gray-500">{{ step.description }}</div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
