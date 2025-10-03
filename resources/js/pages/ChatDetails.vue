<script setup lang="ts">
import {
  Card,
  CardContent,
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
import { dashboard } from '@/routes';
import { show } from '@/actions/App/Http/Controllers/FileController';
import { type BreadcrumbItem } from '@/types';
import { Check, Circle, Dot } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Head, Form, Link } from '@inertiajs/vue3';
import { Ellipsis, Trash } from "lucide-vue-next";
import { ScrollArea } from '@/components/ui/scroll-area';
import store from '@/actions/App/Http/Controllers/FileChatStoreController';
import chatDetails from '@/actions/App/Http/Controllers/FileChatDetailsController';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    file: any;
    conversation: any;
    messages: any;
    conversations: any;
}>();

const disabledButton = ref(false);

if(props.messages.data.length > 0 && props.messages.data[props.messages.data.length - 1].participant === 'user') {
    disabledButton.value = true;
}

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

const handleSuccess = () => {
    console.log('success');
    disabledButton.value = true;
}

const handleError = () => {
    console.log('error');
}

onMounted(() => {
    console.log('mounted');
})
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
                        <div class="relative h-full flex flex-col items-center justify-between">
                            <ScrollArea class="w-full rounded-md h-[100%] border p-2">
                                <div class="flex flex-col space-y-2 border-4">
                                    <div v-for="message in messages.data" :key="message.id" class="flex" :class="message.participant === 'user' ? 'justify-end' : 'justify-start'">
                                        <div class="text-white rounded-4xl p-4  max-w-[70%]" :class="message.participant === 'user' ? 'bg-zinc-800' : 'bg-gray-900'">
                                            <p>
                                                {{ message.message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </ScrollArea>
                            <Form
                                :action="store.url({ file: file.data.uuid, conversation: conversation.data.uuid })"
                                method="post"
                                disableWhileProcessing
                                @success="handleSuccess"
                                @error="handleError"
                                resetOnSuccess
                                class="absolute bottom-0 w-full px-1"
                                #default="{
                                    processing,
                                    errors,
                                    reset
                                }"
                            >
                                <textarea
                                    rows="2"
                                    name="message"
                                    class="scrollbar-thin scrollbar-rounded bg-zinc-900  w-full p-4 border border-gray-300 rounded-4xl resize-none focus:outline-none focus:ring-1 focus:ring-gray-500 transition-all"
                                    placeholder="Type your message..."
                                ></textarea>
                                <Button
                                    class="absolute right-4 bottom-4 bg-blue-600 text-white rounded-full focus:outline-none hover:bg-blue-700 transition-all "
                                    :class="disabledButton ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                    type="submit"
                                    :disabled="processing || disabledButton"
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
                            </Form>
                        </div>
                    </div>
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
                                <Card>
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
