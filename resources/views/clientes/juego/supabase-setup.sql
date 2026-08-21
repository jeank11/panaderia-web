-- Candy Panadería Echeveste - Ranking online
-- Ejecutar en Supabase SQL Editor.
-- Requiere habilitar Anonymous Sign-Ins en Authentication > Sign In / Providers.

create table if not exists public.player_rankings (
    user_id uuid primary key references auth.users(id) on delete cascade,
    name text not null check (char_length(trim(name)) between 3 and 15),
    total_score integer not null default 0 check (total_score >= 0),
    total_stars integer not null default 0 check (total_stars >= 0),
    best_level integer not null default 0 check (best_level >= 0 and best_level <= 60),
    levels_completed integer not null default 0 check (levels_completed >= 0 and levels_completed <= 60),
    scores_by_level jsonb not null default '{}'::jsonb,
    stars_by_level jsonb not null default '{}'::jsonb,
    updated_at timestamptz not null default now()
);

alter table public.player_rankings enable row level security;

revoke all on table public.player_rankings from anon;
grant select, insert, update on table public.player_rankings to authenticated;

create policy "Ranking is public to signed-in players"
on public.player_rankings
for select
to authenticated
using (true);

create policy "Anonymous players can create their own ranking"
on public.player_rankings
for insert
to authenticated
with check (
    user_id = (select auth.uid())
    and coalesce((select (auth.jwt()->>'is_anonymous')::boolean), false) = true
);

create policy "Anonymous players can update their own ranking"
on public.player_rankings
for update
to authenticated
using (
    user_id = (select auth.uid())
    and coalesce((select (auth.jwt()->>'is_anonymous')::boolean), false) = true
)
with check (
    user_id = (select auth.uid())
    and coalesce((select (auth.jwt()->>'is_anonymous')::boolean), false) = true
);

create index if not exists player_rankings_score_idx
    on public.player_rankings (total_score desc, best_level desc, total_stars desc);
