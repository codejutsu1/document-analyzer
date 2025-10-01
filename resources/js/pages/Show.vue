<script setup lang="ts">
import {
  Card,
  CardContent,
} from '@/components/ui/card';
import { 
    Stepper, 
    StepperDescription, 
    StepperItem, 
    StepperSeparator, 
    StepperTitle, 
    StepperTrigger 
} from "@/components/ui/stepper";
import { Badge } from '@/components/ui/badge'
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index, show } from '@/actions/App/Http/Controllers/FileController';
import { type BreadcrumbItem } from '@/types';
import { Check, Circle, Dot, Loader2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    file: any;
}>();

const chunkingStatus = ref(props.file.data.chunking_status);
const embeddingStatus = ref(props.file.data.embedding_status);
const storageStatus = ref(props.file.data.storage_status);

const progressPercentage = ref(0);


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
            console.log("Chunking Status: ", e.file.chunking_status);
            console.log("Embedding Status: ", e.file.embedding_status);
            console.log("Storage Status: ", e.file.storage_status);

            chunkingStatus.value = e.file.chunking_status;
            embeddingStatus.value = e.file.embedding_status;
            storageStatus.value = e.file.storage_status;

            if(e.file.chunking_status === 'completed') {
                progressPercentage.value = 33;
            }

            if(e.file.chunking_status === 'completed' && e.file.embedding_status === 'completed') {
                progressPercentage.value = 66;
            }
            if(e.file.chunking_status === 'completed' && e.file.embedding_status === 'completed' && e.file.storage_status === 'completed') {
                progressPercentage.value = 100;
            }
        });
});

    if(chunkingStatus.value === 'completed') {
        progressPercentage.value = 33;
    }

    if(chunkingStatus.value === 'completed' && embeddingStatus.value === 'completed') {
        progressPercentage.value = 66;
    }
    if(chunkingStatus.value === 'completed' && embeddingStatus.value === 'completed' && storageStatus.value === 'completed') {
        progressPercentage.value = 100;
    }



const steps = [
  {
    step: 1,
    title: "Chunking",
    description:
        "Splitting file into chunks",
    status: chunkingStatus,
    },
  {
    step: 2,
    title: "Embeddings",
    description: "Generating embeddings",
    status: embeddingStatus,
    },
  {
    step: 3,
    title: "Storage",
    description:
        "Saving embeddings to vector storage",
    status: storageStatus,
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
                                        <span v-if="file?.data?.status === 'active'" class="font-medium w-4/5"><Badge variant="outline" class="bg-green-500">Completed</Badge></span>
                                        <span v-if="file?.data?.status === 'failed'" class="font-medium w-4/5"><Badge variant="outline" class="bg-red-500">Failed</Badge></span>
                                        <span v-if="file?.data?.status === 'processing'" class="font-medium w-4/5"><Badge variant="outline" class="bg-yellow-500"><Loader2 class="w-10 h-10 animate-spin"></Loader2>Processing</Badge></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </CardContent>
                </Card>
                <div>
                    <!-- <Stepper orientation="vertical" class="mx-auto flex w-full max-w-md flex-col justify-start gap-10">
                        <StepperItem
                            v-for="step in steps"
                            :key="step.step"
                            v-slot="{ state }"
                            class="relative flex w-full items-start gap-6"
                            :step="step.step"
                        >
                        <StepperSeparator
                            v-if="step.step !== steps[steps.length - 1].step"
                            class="absolute left-[18px] top-[38px] block h-[105%] w-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                        />

                        <StepperTrigger as-child>
                            <Button
                                :variant="step.status.value === 'completed' || step.status.value === 'active' ? 'default' : 'outline'"
                                size="icon"
                                class="z-10 rounded-full shrink-0 pointer-events-none"
                                :class="[step.status.value === 'pending' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                            >
                                <Check v-if="step.status.value === 'completed'" class="size-5" />
                                <Circle v-if="step.status.value === 'active'" />
                                <Dot v-if="step.status.value === 'failed'" />
                            </Button>
                        </StepperTrigger>

                        <div class="flex flex-col gap-1">
                            <StepperTitle
                            :class="[state === 'active' && 'text-primary']"
                            class="text-sm font-semibold transition lg:text-base"
                            >
                            {{ step.title }}
                            </StepperTitle>
                            <StepperDescription
                            :class="[state === 'active' && 'text-primary']"
                            class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm"
                            >
                            {{ step.description }}
                            </StepperDescription>
                        </div>
                        </StepperItem>
                    </Stepper> -->

                    <div class="max-w-3xl mx-auto p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                            <h3 class="text-lg font-semibold">Processing status</h3>
                            <p class="text-sm text-gray-500">Background pipeline progress and logs</p>
                            </div>
                        </div>

                        <!-- Progress bar -->
                        <div class="relative h-3 rounded-full bg-gray-200 overflow-hidden mb-6">
                            <!-- filled portion -->
                            <div class="absolute left-0 top-0 h-full bg-blue-500 rounded-full transition-all duration-500 ease-out" :style="{ width: progressPercentage + '%' }"></div>
                        </div>


                        <!-- Details / timeline -->
                        <div class="space-y-3 shadow-sm divide-y">
                            <Card v-for="step in steps" :key="step.step">
                                <CardContent>
                                    <div class="flex gap-4 items-start">
                                        <div v-if="step.status.value === 'completed'" class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-medium">✓</div>
                                        <div v-if="step.status.value === 'active'" class="w-8 h-8 rounded-full border-2 border-blue-600 bg-white text-blue-600 flex items-center justify-center text-sm font-medium animate-pulse">●</div>
                                        <div v-if="step.status.value === 'pending'" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">{{ step.step }}</div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                            <div>
                                                <div 
                                                    class="text-sm font-semibold"
                                                    :class="step.status.value === 'completed' ? 'text-green-600' : (step.status.value === 'active' ? 'text-blue-600 animate-pulse' : 'text-gray-600')"
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
