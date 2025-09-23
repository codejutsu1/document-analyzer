<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import {
  Drawer,
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
  DrawerTrigger,
} from "@/components/ui/drawer";
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard,file } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { BotMessageSquare, File } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Files',
        href: file().url,
    },
];

</script>


<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs" vaul-drawer-wrapper>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Drawer>
                <DrawerTrigger as-child>
                    <div class="flex justify-end">
                        <Button class="cursor-pointer">Upload File</Button>
                    </div>
                </DrawerTrigger>
                <DrawerContent>
                    <div class="mx-auto w-full max-w-sm">
                        <DrawerHeader>
                            <DrawerTitle>Upload PDF File</DrawerTitle>
                            <DrawerDescription>Select a PDF file to upload for AI analysis</DrawerDescription>
                        </DrawerHeader>
                        <div class="p-4 pb-0">
                            <div class="flex items-center justify-center space-x-2">

                                hii
                            </div>
                        </div>
                        <DrawerFooter>
                            <Button>Submit</Button>
                            <DrawerClose>
                            <Button variant="outline">
                                Cancel
                            </Button>
                            </DrawerClose>
                        </DrawerFooter>
                    </div>
                </DrawerContent>       
            </Drawer>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card>
                    <CardContent>
                       <div class="flex gap-x-1 gap-y-2">
                           <div class="flex items-center w-1/3">
                               <img src="pdf-image.png" alt="Image of Pdf" class="w-20 h-20">
                           </div>
                          <div class="flex flex-col justify-center items-center w-3/5">
                            <div class="flex flex-col items-center justify-between gap-2 h-full">
                                <p class="text-lg font-medium">The History of AI</p>
                                <p class="text-xs text-gray-500">23 Aug 2025 - (2 MB)</p>
                            </div>
                          </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full cursor-pointer">
                            <BotMessageSquare class="mr-2 h-4 w-4" /> Chat AI Assistant
                        </Button>
                    </CardFooter>
                </Card>

                <Card>
                    <CardContent>
                       <div class="flex gap-y-2 gap-x-1">
                           <div class="flex items-center w-1/3">
                               <img src="pdf-image.png" alt="Image of Pdf" class="w-20 h-20">
                           </div>
                          <div class="flex flex-col justify-center items-center w-3/5">
                            <div class="flex flex-col items-center justify-between gap-2 h-full">
                                <p class="font-medium">Foundational Level of Twister Malicious</p>
                                <p class="text-xs text-gray-500">23 Aug 2025 - (2 MB)</p>
                            </div>
                          </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full cursor-pointer">
                            <BotMessageSquare class="mr-2 h-4 w-4" /> Chat AI Assistant
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
