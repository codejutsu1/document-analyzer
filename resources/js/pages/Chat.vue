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
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge'
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, show } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Check, Circle, Dot } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { Ellipsis, Trash } from "lucide-vue-next";
import { ScrollArea } from '@/components/ui/scroll-area';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Files',
        href: show().url,
    },
    {
        title: 'History of AI',
        href: show().url,
    },
];
</script>


<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full border flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
           <div class="flex gap-4 h-[500px]">
                <div class="w-[70%]  h-[90%]">
                    <div class="h-full">
                        <div class="hidden flex flex-col border space-y-4 w-full h-full items-center justify-center">
                            <p class="text-lg font-medium">Hello, whats the agenda today?</p>
                            <!-- Chat input box -->
                            <div class="relative w-4/5">
                                <textarea
                                rows="2"
                                class="scrollbar-thin scrollbar-rounded  w-full p-4 border border-gray-300 rounded-4xl resize-none focus:outline-none focus:ring-1 focus:ring-gray-500 transition-all"
                                placeholder="Type your message..."
                                ></textarea>
                                <Button
                                class="absolute right-4 bottom-4 bg-blue-600 text-white rounded-full focus:outline-none hover:bg-blue-700 transition-all"
                                >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                    />
                                </svg>
                            </Button>
                            </div>
                        </div>

                        <div class="relative h-full flex flex-col items-center justify-between">
                            <ScrollArea class="w-full rounded-md h-full p-2">
                                <div class="space-y-2">
                                    <div class="flex justify-end">
                                        <div class="bg-zinc-800 text-white rounded-4xl p-4">
                                            <p>What is the capital of France?</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-gray-900 text-white rounded-4xl p-4 max-w-[70%]">
                                            <p>The capital of France is Paris and there are plenty of other cities to visit.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <div class="bg-zinc-800 text-white rounded-4xl p-4">
                                            <p>What is the capital of France?</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-gray-900 text-white rounded-4xl p-4 max-w-[70%]">
                                            <p>The capital of France is Paris and there are plenty of other cities to visit.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <div class="bg-zinc-800 text-white rounded-4xl p-4">
                                            <p>What are the cities in France    ?</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-gray-900 text-white rounded-4xl p-4 max-w-[70%]">
                                            <p>The cities in France are Paris, Lyon, Marseille, etc.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <div class="bg-zinc-800 text-white rounded-4xl p-4">
                                            <p>What is the population of France?</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-gray-900 text-white rounded-4xl p-4 max-w-[70%]">
                                            <p>The population of France is 67 million.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <div class="bg-zinc-800 text-white rounded-4xl p-4">
                                            <p>What is the currency of France?</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-gray-900 text-white rounded-4xl p-4 max-w-[70%]">
                                            <p>The currency of France is Euro.</p>
                                        </div>
                                    </div>
                                </div>
                            </ScrollArea>
                            <div class="absolute bottom-0 w-full px-1">
                                <textarea
                                rows="2"
                                class="scrollbar-thin scrollbar-rounded bg-zinc-900  w-full p-4 border border-gray-300 rounded-4xl resize-none focus:outline-none focus:ring-1 focus:ring-gray-500 transition-all"
                                placeholder="Type your message..."
                                ></textarea>
                                <Button
                                class="absolute right-4 bottom-4 bg-blue-600 text-white rounded-full focus:outline-none hover:bg-blue-700 transition-all"
                                >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                    />
                                </svg>
                            </Button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/3 h-[90%] border rounded-lg py-4 px-2">
                    <h1 class="text-lg font-bold text-center">Chat History</h1>
                    <ScrollArea class="h-[90%] w-full rounded-md">
                        <div class="space-y-2 mt-2">
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">What is the capital of France?</p>
                                        </div>
                                        <div>
                                            <DropdownMenu>
                                                <DropdownMenuTrigger class="cursor-pointer"> <Ellipsis /></DropdownMenuTrigger>
                                                <DropdownMenuContent>
                                                    <DropdownMenuItem class="cursor-pointer">
                                                        <Trash class="text-red-500" />
                                                        Delete Chat
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Is the law the law?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Hey, how are you doing?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Hey, how are you doing?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Hey, how are you doing?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Hey, how are you doing?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                            <Card>
                                <CardContent>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium">Hey, how are you doing?</p>
                                        </div>
                                        <div>
                                            <Ellipsis />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                        </div>
                    </ScrollArea>
                </div>  
           </div>
        </div>
    </AppLayout>
</template>
