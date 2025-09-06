import api from '../axios'
import type { Role } from './users'

export const fetchRoles = (token: string) =>
  api.get<{ data: Role[] }>('/roles', { headers: { Authorization: `Bearer ${token}` } })
