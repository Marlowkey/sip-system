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
      route: 'dashboard',
      icon: mdiMonitor,
      label: 'Dashboard'
    },
    {
      route: 'tables',
      label: 'Tables',
      icon: mdiTable
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
