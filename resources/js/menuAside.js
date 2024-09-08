import {
  mdiAccountCircle,
  mdiMonitor,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
} from '@mdi/js'


export default [
    {
      route: 'home',
      icon: mdiMonitor,
      label: 'Dashboard'
    },
    {
      route: 'tables',
      label: 'Tables',
      icon: mdiTable
    },
    {
      route: 'journals.index',
      label: 'Journal',
      icon: mdiSquareEditOutline
    },
    {
      route: 'error',
      label: 'Error',
      icon: mdiAlertCircle
    }
]
