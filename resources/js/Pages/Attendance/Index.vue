<script setup>
    import {
        Head,
        useForm,
        router
    } from '@inertiajs/vue3'
    import debounce from 'lodash.debounce'
    import {
        computed,
        ref,
        onMounted,
        watch
    } from 'vue'
    import {
        useMainStore
    } from '@/stores/main.js'
    import {
        mdiLocationEnter,
        mdiPlus,
        mdiClockCheckOutline,
        mdiFileExport,
    } from '@mdi/js'
    import SectionMain from '@/components/SectionMain.vue'
    import CardBox from '@/components/CardBox.vue'
    import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
    import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
    import BaseButton from '@/components/BaseButton.vue'
    import NotificationBar from '@/components/NotificationBar.vue'
    import TableStudentAttendance from '@/components/TableStudentAttendance.vue'
    import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'
    import TableCoordinatorAttendance from '@/components/TableCoordinatorAttendance.vue'

    import FormControl from '@/components/FormControl.vue'


    const props = defineProps({
        user: Object, // Authenticated user passed as a prop from Inertia
        attendance: Array, // Documents passed as a prop from Inertia
        studentAttendance: Array, // Student attendance passed as a prop from Inertia
        date: String, // Date passed as a prop from Inertia
        month: String,
        currentSession: String,
    })



    const getDateNow = () => {
        return new Date().toISOString().substr(0, 10)
    }

    const getMonthNow = () => {
        return new Date().toISOString().substr(0, 7)
    }

    const date = ref(props.date ?? getDateNow())
    const month = ref(props.month ?? getMonthNow())
    const userId = ref(props.user?.id);
    const url = route('attendances.index')


    const fetchAttendancesForCoordinator = debounce(() => {
        router.get(url, {
            date: date.value
        });
    }, 1500);

    const fetchAttendancesForStudent = debounce(() => {
        router.get(url, {
            month: month.value
        });
    }, 1500);

    watch(date, (newDate, oldDate) => {
        if (newDate !== oldDate) {
            fetchAttendancesForCoordinator();
        }
    });

    watch(month, (newMonth, oldMonth) => {
        if (newMonth !== oldMonth) {
            fetchAttendancesForStudent();
        }
    });

    const isItemEmpty = ((item) => {
        return item.length === 0;
    })

    const userRole = props.user.role;

    // const form = useForm({
    //     month: props.month,
    //     user_id: userId.value,
    // })

    const exportAttendance = () => {
        console.log('Month:', month.value);
        console.log('User ID:', userId.value);

        form.post(route('attendances.export'), {
            month: month.value,
            user_id: userId.value,
        }, {
            onSuccess: () => {
                reset();
            }
        });
    }

    const form = useForm({
        type: '', // Attendance type (time_in_am, time_out_am, etc.)
    });

    const logAttendance = (type) => {
        form.type = type; // Dynamically set the attendance type
        form.post(route('attendances.store'), {
            onSuccess: () => {
                NotificationBar({
                    message: `${type.replace('_', ' ').toUpperCase()} logged successfully.`,
                    color: 'success',
                });
            },
            onError: (errors) => {
                console.error('Error logging attendance:', errors);
            },
        });
    };
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain v-if="userRole === 'student'">

            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page . props . flash . message }}
            </NotificationBar>
            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main>
                <div class="flex flex-wrap gap-4 items-center justify-end">
                    <!-- Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Export Button -->
                        <BaseButton label="Export" roundedFull small :icon="mdiFileExport" color="success"
                            :href="route('attendances.export', { month: month, user_id: userId })" class="p-2" />
                    </div>
                    <!-- Month Picker -->
                    <div class=" sm:w-auto">
                        <FormControl v-model="month" borderless type="month" placeholder="Select Date"
                            class="text-sm font-medium w-full sm:w-auto" />
                    </div>
                </div>
                <!-- Instructions and Buttons Section -->

            </SectionTitleLineWithButton>
            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Instructions -->
                    <p class="text-base text-gray-600 dark:text-gray-300 relative pb-2">
    Click the appropriate button to log your automatic Time In/Time Out based on the current
    session.
    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black mt-1"></span>
</p>

                    <!-- Buttons -->
                    <div class="flex flex-wrap gap-4 justify-end">
                        <!-- Time In AM -->
                        <BaseButton v-if="currentSession === 'AM'" label="Time In (AM)" roundedFull small
                            :icon="mdiClockCheckOutline" color="info" class="p-2"
                            :disabled="props.attendance?.time_in_am" @click="logAttendance('time_in_am')" />

                        <!-- Time Out AM -->
                        <BaseButton v-if="currentSession === 'AM'" label="Time Out (AM)" roundedFull small
                            :icon="mdiClockCheckOutline" color="danger" class="p-2"
                            :disabled="props.attendance?.time_out_am" @click="logAttendance('time_out_am')" />

                        <!-- Time In PM -->
                        <BaseButton v-if="currentSession === 'PM'" label="Time In (PM)" roundedFull small
                            :icon="mdiClockCheckOutline" color="info" class="p-2"
                            :disabled="props.attendance?.time_in_pm" @click="logAttendance('time_in_pm')" />

                        <!-- Time Out PM -->
                        <BaseButton v-if="currentSession === 'PM'" label="Time Out (PM)" roundedFull small
                            :icon="mdiClockCheckOutline" color="danger" class="p-2"
                            :disabled="props.attendance?.time_out_pm" @click="logAttendance('time_out_pm')" />
                    </div>
                </div>
            </div>


            <CardBoxComponentEmpty v-if="isItemEmpty(props.attendance)" />
            <CardBox has-table v-else>
                <TableStudentAttendance :attendance="attendance" />
            </CardBox>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page . props . flash . message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main>
                <FormControl v-model="date" borderless type="date" placeholder="Select Date"
                    class="mx-2 text-sm font-medium " />
            </SectionTitleLineWithButton>
            <CardBoxComponentEmpty v-if="isItemEmpty(props.studentAttendance)" />
            <TableCoordinatorAttendance v-else :attendance="studentAttendance" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
