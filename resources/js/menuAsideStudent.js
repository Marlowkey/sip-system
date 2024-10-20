import {
    mdiAccountCircle,
    mdiMonitor,
    mdiLock,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiTable,
    mdiAccountFile,
    mdiViewList,
    mdiTelevisionGuide,
    mdiResponsive,
    mdiPalette,
    mdiCalendarBadge
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Home'
      },
      {
        route: 'documents.index',
        icon: mdiAccountFile,
        label: 'SIP Requirements'
      },
      {
        route: 'attendances.index',
        label: 'Attendance',
        icon: mdiCalendarBadge
      },
      {
        route: 'journals.index',
        label: 'Journal',
        icon: mdiSquareEditOutline
      },
  ]
