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
import { Check, Circle, Dot } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { Loader2 } from "lucide-vue-next";

const props = defineProps<{
    file: any;
}>();

console.log(props.file);


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



const steps = [
  {
    step: 1,
    title: "Text Extracting & Chunking",
    description:
        "The raw text is extracted from the PDF and divided into smaller, manageable chunks. Chunking ensures that each piece of text is within token limits for processing, while still preserving enough context for meaningful understanding.",
  },
  {
    step: 2,
    title: "Generating Embeddings",
    description: "Each text chunk is sent to an AI embedding model, which transforms the text into high-dimensional vector representations. These embeddings capture the semantic meaning of the text so that similar chunks can be compared and retrieved effectively.",
  },
  {
    step: 3,
    title: "Storing Embeddings",
    description:
        "The generated embeddings are stored in a vector database, where they are indexed for efficient similarity search. This allows fast and accurate retrieval of relevant text chunks based on semantic queries, powering downstream applications like question answering or content search",
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
                    <Stepper orientation="vertical" class="mx-auto flex w-full max-w-md flex-col justify-start gap-10">
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
                            :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                            size="icon"
                            class="z-10 rounded-full shrink-0"
                            :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                            >
                            <Check v-if="state === 'completed'" class="size-5" />
                            <Circle v-if="state === 'active'" />
                            <Dot v-if="state === 'inactive'" />
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
                    </Stepper>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
