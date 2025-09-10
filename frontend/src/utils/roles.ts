export function hasRole(user: any, roles: string | string[]): boolean {
  if (!user || !user.role) return false; // если нет пользователя или роли
  if (!Array.isArray(roles)) roles = [roles]; // если передана одна роль, преобразуем в массив
  return roles.includes(user.role.name); // проверяем user.role.name
}
