import {
    mdiMonitor,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiCalendarBadge,
    mdiAccountFile,
    mdiDomain
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Dashboard'
      },
      {
        route: 'documents.index',
        icon: mdiAccountFile,
        label: 'SIP Requirements'
      },
      {
        route: 'attendances.index',
        icon: mdiCalendarBadge,
        label: 'Attendance',
      },
      {
        route: 'journals.index',
        label: 'Journal',
        icon: mdiSquareEditOutline
      },
        {
              route: 'users-student-hte.index',
              icon: mdiDomain   ,
              label: 'Student HTE Placements'
        },
      {
        route: 'htes.index',
        icon: mdiDomain   ,
        label: 'Host Training Establishments'
      },
  ]
