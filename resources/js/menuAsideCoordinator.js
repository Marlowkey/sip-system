import {
    mdiMonitor,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiCalendarBadge,
    mdiAccountFile
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
        label: 'DTR',
      },
      {
        route: 'forms',
        label: 'Forms',
        icon: mdiSquareEditOutline
      },
      {
        route: 'error',
        label: 'Error',
        icon: mdiAlertCircle
      }
  ]
