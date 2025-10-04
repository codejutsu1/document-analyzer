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
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge'
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { show } from '@/actions/App/Http/Controllers/FileController';
import { type BreadcrumbItem } from '@/types';
import { Check, Circle, Dot } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head, Form, usePage, router, Link } from '@inertiajs/vue3';
import { Ellipsis, Trash } from "lucide-vue-next";
import { ScrollArea } from '@/components/ui/scroll-area';
import store from '@/actions/App/Http/Controllers/FileChatStoreController';
import chatDetails from '@/actions/App/Http/Controllers/FileChatDetailsController';
import { toast } from 'vue-sonner';

const page = usePage();

const props = defineProps<{
    file: any;
    conversations: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Files',
        href: show(props?.file?.data?.uuid).url,
    },
    {
        title: 'History of AI',
        href: show(props?.file?.data?.uuid).url,
    },
];

const handleError = () => {
    console.log('handleError');
    console.log(page);
    if(page?.props?.flash?.message) {
        toast.error("Error", {
            description: page?.message,
        });
    } 
}

const handleSuccess = () => {
    console.log(page.props.flash);
    console.log('handleSuccess');
    console.log(props.file.data.uuid);
    console.log(page?.props?.flash?.data?.conversation_uuid);
    router.visit(chatDetails.url({
  file: props.file.data.uuid,
  conversation: page?.props?.flash?.data?.conversation_uuid
}));
    console.log(true);
}
</script>


<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full border flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
           <div class="flex gap-4 h-[500px]">
                <div class="w-[70%]  h-[90%]">
                        <Form 
                            :action="store.url({ file: file.data.uuid })"
                            method="post"
                            disableWhileProcessing
                            @success="handleSuccess"
                            @error="handleError"
                            resetOnSuccess
                            #default="{
                                processing,
                                errors,
                                reset
                            }"

                            class="w-full h-full"
                        >
                            <div class="flex flex-col border space-y-4 w-full h-full items-center justify-center">
                                <p class="text-lg font-medium">Hello, whats the agenda today?</p>
                                <!-- Chat input box -->
                                <div class="relative w-4/5">
                                    <textarea
                                        rows="2"
                                        name="message"
                                        class="scrollbar-thin scrollbar-rounded  w-full p-4 border border-gray-300 rounded-4xl resize-none focus:outline-none focus:ring-1 focus:ring-gray-500 transition-all"
                                        placeholder="Type your message..."
                                    ></textarea>
                                    <Button
                                        type="submit" 
                                        :disabled="processing" 
                                        class="absolute right-4 bottom-4 bg-blue-600 text-white rounded-full focus:outline-none hover:bg-blue-700 transition-all cursor-pointer"
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
                        </Form>
                </div>
                <div class="w-1/3 h-[90%] border rounded-lg py-4 px-2">
                    <h1 class="text-lg font-bold text-center">Chat History</h1>
                    <ScrollArea class="h-[90%] w-full rounded-md">
                        <div class="mt-2 space-y-2">
                            <Link
                             v-for="conversation in conversations.data" :key="conversation.id"
                             :href="chatDetails.url({ file: file.data.uuid, conversation: conversation.uuid })"
                             class="block mt-3"
                            >
                                <Card class="hover:bg-zinc-900">
                                    <CardContent>
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium">{{ conversation.message  }}</p>
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
                            </Link>
                        </div>
                    </ScrollArea>
                </div>  
           </div>
        </div>
    </AppLayout>
</template>
