import api from '../axios'

export interface Role { id: number; name: string }
export interface User {
  id: number
  name: string
  first_name?: string
  last_name?: string
  middle_name?: string
  email: string
  role?: Role
}

export const fetchUsers = (token: string) =>
  api.get<{ data: User[] }>('/users', { headers: { Authorization: `Bearer ${token}` } })

export const updateUser = (user: User, token: string) =>
  api.put(`/users/${user.id}`, user, { headers: { Authorization: `Bearer ${token}` } })

export const assignRole = (userId: number, roleId: number, token: string) =>
  api.post(`/users/${userId}/assign-role`, { role_id: roleId }, { headers: { Authorization: `Bearer ${token}` } })

export const deleteUser = (userId: number, token: string) =>
  api.delete(`/users/${userId}`, { headers: { Authorization: `Bearer ${token}` } })

export const changePassword = (userId: number, payload: any, token: string) =>
  api.post(`/users/${userId}/change-password`, payload, { headers: { Authorization: `Bearer ${token}` } })
