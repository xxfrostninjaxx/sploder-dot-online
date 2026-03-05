--
-- PostgreSQL database dump
--

\restrict 4AaagqC8O1GZmrPI2TvwEiCy8dQxSsRIqMKLOqUU82quyaFndC2NsZH9QmhdfOS

-- Dumped from database version 17.6 (Debian 17.6-1.pgdg13+1)
-- Dumped by pg_dump version 17.6 (Debian 17.6-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: sploder; Type: DATABASE; Schema: -; Owner: sploder
--

CREATE DATABASE sploder WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE sploder OWNER TO sploder;

\unrestrict 4AaagqC8O1GZmrPI2TvwEiCy8dQxSsRIqMKLOqUU82quyaFndC2NsZH9QmhdfOS
\connect sploder
\restrict 4AaagqC8O1GZmrPI2TvwEiCy8dQxSsRIqMKLOqUU82quyaFndC2NsZH9QmhdfOS

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: award_requests; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.award_requests (
    id integer NOT NULL,
    username text NOT NULL,
    membername text NOT NULL,
    level integer NOT NULL,
    category text,
    style integer NOT NULL,
    material integer NOT NULL,
    icon integer NOT NULL,
    color integer NOT NULL,
    message text,
    is_viewed boolean NOT NULL
);


ALTER TABLE public.award_requests OWNER TO sploder;

--
-- Name: award_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.award_requests ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.award_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: awards; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.awards (
    id integer NOT NULL,
    username text NOT NULL,
    membername text NOT NULL,
    level integer NOT NULL,
    category text,
    style integer NOT NULL,
    material integer NOT NULL,
    icon integer NOT NULL,
    color integer NOT NULL,
    message text
);


ALTER TABLE public.awards OWNER TO sploder;

--
-- Name: awards_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.awards ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.awards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: awards_sent; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.awards_sent (
    username text NOT NULL,
    creationdate integer NOT NULL
);


ALTER TABLE public.awards_sent OWNER TO sploder;

--
-- Name: banned_members; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.banned_members (
    username text NOT NULL,
    banned_by text NOT NULL,
    reason text NOT NULL,
    bandate integer NOT NULL,
    autounbandate integer NOT NULL
);


ALTER TABLE public.banned_members OWNER TO sploder;

--
-- Name: challenge_winners; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.challenge_winners (
    winner_id integer NOT NULL,
    g_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.challenge_winners OWNER TO sploder;

--
-- Name: challenge_winners_winner_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.challenge_winners ALTER COLUMN winner_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.challenge_winners_winner_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: challenges; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.challenges (
    challenge_id integer NOT NULL,
    g_id integer NOT NULL,
    mode boolean NOT NULL,
    challenge integer NOT NULL,
    prize integer NOT NULL,
    winners integer NOT NULL,
    verified boolean NOT NULL,
    insert_date date NOT NULL
);


ALTER TABLE public.challenges OWNER TO sploder;

--
-- Name: challenges_c_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.challenges ALTER COLUMN challenge_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.challenges_c_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: collection_games; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.collection_games (
    collection_id integer NOT NULL,
    g_id integer NOT NULL
);


ALTER TABLE public.collection_games OWNER TO sploder;

--
-- Name: collections; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.collections (
    collection_id integer NOT NULL,
    userid integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL
);


ALTER TABLE public.collections OWNER TO sploder;

--
-- Name: collections_collection_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.collections ALTER COLUMN collection_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.collections_collection_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: comment_votes; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.comment_votes (
    id integer NOT NULL,
    username text NOT NULL,
    vote integer NOT NULL
);


ALTER TABLE public.comment_votes OWNER TO sploder;

--
-- Name: comments; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.comments (
    venue text NOT NULL,
    id integer NOT NULL,
    thread_id integer NOT NULL,
    creator_name text NOT NULL,
    body text NOT NULL,
    score integer NOT NULL,
    "timestamp" integer NOT NULL
);


ALTER TABLE public.comments OWNER TO sploder;

--
-- Name: comments_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.comments ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: contest_nominations; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.contest_nominations (
    g_id integer NOT NULL,
    nominator_username text NOT NULL
);


ALTER TABLE public.contest_nominations OWNER TO sploder;

--
-- Name: contest_voter_usernames; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.contest_voter_usernames (
    id integer NOT NULL,
    voter_username text NOT NULL
);


ALTER TABLE public.contest_voter_usernames OWNER TO sploder;

--
-- Name: contest_votes; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.contest_votes (
    id integer NOT NULL,
    votes integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.contest_votes OWNER TO sploder;

--
-- Name: contest_winner; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.contest_winner (
    contest_id integer NOT NULL,
    g_id integer NOT NULL
);


ALTER TABLE public.contest_winner OWNER TO sploder;

--
-- Name: featured_games; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.featured_games (
    feature_id integer NOT NULL,
    g_id integer NOT NULL,
    feature_date timestamp without time zone NOT NULL,
    editor_userid integer NOT NULL
);


ALTER TABLE public.featured_games OWNER TO sploder;

--
-- Name: featured_games_feature_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.featured_games ALTER COLUMN feature_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.featured_games_feature_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: friend_requests; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.friend_requests (
    request_id integer NOT NULL,
    sender_id integer NOT NULL,
    receiver_id integer NOT NULL,
    sender_username text NOT NULL,
    receiver_username text NOT NULL,
    is_viewed boolean DEFAULT false NOT NULL
);


ALTER TABLE public.friend_requests OWNER TO sploder;

--
-- Name: friend_requests_request_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.friend_requests ALTER COLUMN request_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.friend_requests_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: friends; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.friends (
    id integer NOT NULL,
    user1 text NOT NULL,
    user2 text NOT NULL,
    bested boolean NOT NULL
);


ALTER TABLE public.friends OWNER TO sploder;

--
-- Name: friends_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.friends ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.friends_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: game_tags; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.game_tags (
    g_id integer NOT NULL,
    tag text NOT NULL
);


ALTER TABLE public.game_tags OWNER TO sploder;

--
-- Name: game_views_anonymous; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.game_views_anonymous (
    g_id integer NOT NULL,
    ipaddress character varying(45) NOT NULL,
    create_date timestamp without time zone DEFAULT (now() AT TIME ZONE 'utc'::text) NOT NULL
);


ALTER TABLE public.game_views_anonymous OWNER TO sploder;

--
-- Name: game_views_members; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.game_views_members (
    g_id integer NOT NULL,
    userid integer NOT NULL,
    create_date timestamp without time zone DEFAULT (now() AT TIME ZONE 'utc'::text) NOT NULL
);


ALTER TABLE public.game_views_members OWNER TO sploder;

--
-- Name: games; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.games (
    g_id integer NOT NULL,
    author text NOT NULL,
    title text NOT NULL,
    date timestamp without time zone NOT NULL,
    description text,
    g_swf integer NOT NULL,
    ispublished integer DEFAULT 0 NOT NULL,
    isdeleted integer DEFAULT 0 NOT NULL,
    isprivate integer DEFAULT 1 NOT NULL,
    comments integer DEFAULT 0 NOT NULL,
    user_id integer NOT NULL,
    views integer DEFAULT 0 NOT NULL,
    difficulty numeric DEFAULT 5 NOT NULL,
    first_published_date timestamp without time zone NOT NULL,
    last_published_date timestamp without time zone NOT NULL,
    first_created_date timestamp without time zone NOT NULL
);


ALTER TABLE public.games OWNER TO sploder;

--
-- Name: games_backup; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.games_backup (
    g_id integer NOT NULL,
    author text NOT NULL,
    data jsonb NOT NULL
);


ALTER TABLE public.games_backup OWNER TO sploder;

--
-- Name: games_g_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.games ALTER COLUMN g_id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.games_g_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: graphic_likes; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.graphic_likes (
    g_id integer NOT NULL,
    userid integer NOT NULL
);


ALTER TABLE public.graphic_likes OWNER TO sploder;

--
-- Name: graphic_tags; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.graphic_tags (
    g_id integer NOT NULL,
    tag text NOT NULL
);


ALTER TABLE public.graphic_tags OWNER TO sploder;

--
-- Name: graphics; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.graphics (
    id integer NOT NULL,
    version integer NOT NULL,
    userid integer NOT NULL,
    isprivate boolean NOT NULL,
    ispublished boolean NOT NULL
);


ALTER TABLE public.graphics OWNER TO sploder;

--
-- Name: graphics_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.graphics ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.graphics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: leaderboard; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.leaderboard (
    username text NOT NULL,
    pubkey integer NOT NULL,
    gtm integer NOT NULL,
    w boolean NOT NULL
);


ALTER TABLE public.leaderboard OWNER TO sploder;

--
-- Name: members; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.members (
    userid integer NOT NULL,
    username text NOT NULL,
    password text NOT NULL,
    joindate integer NOT NULL,
    lastlogin integer NOT NULL,
    perms text,
    boostpoints integer NOT NULL,
    lastpagechange integer NOT NULL,
    isolate boolean NOT NULL,
    status text,
    ip_address text
);


ALTER TABLE public.members OWNER TO sploder;

--
-- Name: members_userid_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.members ALTER COLUMN userid ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.members_userid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: moderation_logs; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.moderation_logs (
    moderator text NOT NULL,
    action text NOT NULL,
    "on" text NOT NULL,
    "time" timestamp without time zone NOT NULL,
    level integer NOT NULL
);


ALTER TABLE public.moderation_logs OWNER TO sploder;

--
-- Name: pending_deletions; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.pending_deletions (
    g_id integer NOT NULL,
    deleter text NOT NULL,
    reason text,
    "timestamp" timestamp with time zone NOT NULL
);


ALTER TABLE public.pending_deletions OWNER TO sploder;

--
-- Name: reviews; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.reviews (
    review_id integer NOT NULL,
    g_id integer NOT NULL,
    userid integer NOT NULL,
    review text NOT NULL,
    ispublished boolean NOT NULL,
    review_date timestamp without time zone NOT NULL,
    title text NOT NULL
);


ALTER TABLE public.reviews OWNER TO sploder;

--
-- Name: reviews_review_id_seq; Type: SEQUENCE; Schema: public; Owner: sploder
--

ALTER TABLE public.reviews ALTER COLUMN review_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.reviews_review_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: user_info; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.user_info (
    username text NOT NULL,
    description text,
    hobbies text,
    sports text,
    games text,
    movies text,
    bands text,
    respect text
);


ALTER TABLE public.user_info OWNER TO sploder;

--
-- Name: votes; Type: TABLE; Schema: public; Owner: sploder
--

CREATE TABLE public.votes (
    g_id integer NOT NULL,
    username text NOT NULL,
    score integer NOT NULL
);


ALTER TABLE public.votes OWNER TO sploder;

--
-- Name: award_requests award_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.award_requests
    ADD CONSTRAINT award_requests_pkey PRIMARY KEY (id);


--
-- Name: awards awards_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.awards
    ADD CONSTRAINT awards_pkey PRIMARY KEY (id);


--
-- Name: challenge_winners challenge_winners_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenge_winners
    ADD CONSTRAINT challenge_winners_pkey PRIMARY KEY (winner_id);


--
-- Name: challenge_winners challenge_winners_unique; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenge_winners
    ADD CONSTRAINT challenge_winners_unique UNIQUE (g_id, user_id);


--
-- Name: challenges challenges_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (challenge_id);


--
-- Name: collections collections_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.collections
    ADD CONSTRAINT collections_pkey PRIMARY KEY (collection_id);


--
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: contest_winner contest_g_id; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.contest_winner
    ADD CONSTRAINT contest_g_id UNIQUE (g_id);


--
-- Name: featured_games featured_games_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.featured_games
    ADD CONSTRAINT featured_games_pkey PRIMARY KEY (feature_id);


--
-- Name: games g_id; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT g_id UNIQUE (g_id);


--
-- Name: featured_games g_id_featured_games_unique; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.featured_games
    ADD CONSTRAINT g_id_featured_games_unique UNIQUE (g_id);


--
-- Name: games_backup games_backup_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.games_backup
    ADD CONSTRAINT games_backup_pkey PRIMARY KEY (g_id);


--
-- Name: games games_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT games_pkey PRIMARY KEY (g_id);


--
-- Name: graphic_likes graphic_likes_unique; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphic_likes
    ADD CONSTRAINT graphic_likes_unique UNIQUE (g_id, userid);


--
-- Name: graphics graphics_id; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphics
    ADD CONSTRAINT graphics_id UNIQUE (id);


--
-- Name: friends id; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT id PRIMARY KEY (id);


--
-- Name: friend_requests request_id; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.friend_requests
    ADD CONSTRAINT request_id PRIMARY KEY (request_id);


--
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (review_id);


--
-- Name: game_views_anonymous uk_game_views_anonymous_g_id_ipaddress; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_views_anonymous
    ADD CONSTRAINT uk_game_views_anonymous_g_id_ipaddress UNIQUE (g_id, ipaddress);


--
-- Name: game_views_members uk_game_views_members_g_id_userid; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_views_members
    ADD CONSTRAINT uk_game_views_members_g_id_userid UNIQUE (g_id, userid);


--
-- Name: challenges unique_challenges; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT unique_challenges UNIQUE (g_id, challenge_id);


--
-- Name: game_tags unique_game_tags; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_tags
    ADD CONSTRAINT unique_game_tags UNIQUE (g_id, tag);


--
-- Name: graphic_tags unique_graphic_tags; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphic_tags
    ADD CONSTRAINT unique_graphic_tags UNIQUE (g_id, tag);


--
-- Name: members userid; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT userid PRIMARY KEY (userid);


--
-- Name: reviews userid_gid_reviews_unique; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT userid_gid_reviews_unique UNIQUE (userid, g_id);


--
-- Name: members username; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT username UNIQUE (username);


--
-- Name: user_info username_user_info; Type: CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.user_info
    ADD CONSTRAINT username_user_info UNIQUE (username);


--
-- Name: game_tags_g_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX game_tags_g_id ON public.game_tags USING btree (g_id);


--
-- Name: game_tags_tag; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX game_tags_tag ON public.game_tags USING btree (tag);


--
-- Name: graphics_userid; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX graphics_userid ON public.graphics USING btree (userid);


--
-- Name: idx_award_requests_membername; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_award_requests_membername ON public.award_requests USING btree (membername);


--
-- Name: idx_award_requests_username; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_award_requests_username ON public.award_requests USING btree (username);


--
-- Name: idx_award_requests_username_creationdate; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_award_requests_username_creationdate ON public.awards_sent USING btree (username, creationdate);


--
-- Name: idx_awards_membername; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_awards_membername ON public.awards USING btree (membername);


--
-- Name: idx_banned_members_autounbandate; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_banned_members_autounbandate ON public.banned_members USING btree (autounbandate);


--
-- Name: idx_banned_members_username; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_banned_members_username ON public.banned_members USING btree (username);


--
-- Name: idx_challenge_winners_g_id_user_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_challenge_winners_g_id_user_id ON public.challenge_winners USING btree (g_id, user_id);


--
-- Name: idx_challenges_c_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_challenges_c_id ON public.challenges USING btree (challenge_id);


--
-- Name: idx_challenges_date_verified; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_challenges_date_verified ON public.challenges USING btree (insert_date, verified);


--
-- Name: idx_challenges_g_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_challenges_g_id ON public.challenges USING btree (g_id);


--
-- Name: idx_comment_votes; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_comment_votes ON public.comment_votes USING btree (username);


--
-- Name: idx_comments_creator_name; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_comments_creator_name ON public.comments USING btree (creator_name);


--
-- Name: idx_comments_venue_creator_thread; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_comments_venue_creator_thread ON public.comments USING btree (venue, creator_name, thread_id DESC);


--
-- Name: idx_comments_venue_suffix; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_comments_venue_suffix ON public.comments USING btree ("right"(venue, 10));


--
-- Name: idx_friend_requests_reciever_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_friend_requests_reciever_id ON public.friend_requests USING btree (receiver_id);


--
-- Name: idx_friend_requests_sender_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_friend_requests_sender_id ON public.friend_requests USING btree (sender_id);


--
-- Name: idx_friends_user1; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_friends_user1 ON public.friends USING btree (user1);


--
-- Name: idx_games_author; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_author ON public.games USING btree (author);


--
-- Name: idx_games_g_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_g_id ON public.games USING btree (g_id);


--
-- Name: idx_games_is_published_is_private; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_is_published_is_private ON public.games USING btree (ispublished, isprivate);


--
-- Name: idx_games_title_btree; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_title_btree ON public.games USING btree (title);


--
-- Name: idx_games_title_trgm; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_title_trgm ON public.games USING gin (title public.gin_trgm_ops);


--
-- Name: idx_games_user_id; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_games_user_id ON public.games USING btree (user_id);


--
-- Name: idx_reviews_userid; Type: INDEX; Schema: public; Owner: sploder
--

CREATE INDEX idx_reviews_userid ON public.reviews USING btree (userid);


--
-- Name: collection_games collection_id_collection_games_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.collection_games
    ADD CONSTRAINT collection_id_collection_games_fkey FOREIGN KEY (collection_id) REFERENCES public.collections(collection_id) ON DELETE CASCADE;


--
-- Name: comments creator_name_comments_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT creator_name_comments_fkey FOREIGN KEY (creator_name) REFERENCES public.members(username) ON DELETE CASCADE NOT VALID;


--
-- Name: featured_games editor_userid_featured_games_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.featured_games
    ADD CONSTRAINT editor_userid_featured_games_fkey FOREIGN KEY (editor_userid) REFERENCES public.members(userid) ON DELETE CASCADE;


--
-- Name: game_views_anonymous fk_game_views_anonymous_games_g_id; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_views_anonymous
    ADD CONSTRAINT fk_game_views_anonymous_games_g_id FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: game_views_members fk_game_views_members_games_g_id; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_views_members
    ADD CONSTRAINT fk_game_views_members_games_g_id FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: game_views_members fk_game_views_members_members_userid; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_views_members
    ADD CONSTRAINT fk_game_views_members_members_userid FOREIGN KEY (userid) REFERENCES public.members(userid) ON DELETE CASCADE NOT VALID;


--
-- Name: challenge_winners g_id_challenge_winners_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenge_winners
    ADD CONSTRAINT g_id_challenge_winners_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: contest_nominations g_id_contest_nominations_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.contest_nominations
    ADD CONSTRAINT g_id_contest_nominations_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: contest_winner g_id_contest_winner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.contest_winner
    ADD CONSTRAINT g_id_contest_winner_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: challenges g_id_contests_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT g_id_contests_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: featured_games g_id_featured_games_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.featured_games
    ADD CONSTRAINT g_id_featured_games_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE;


--
-- Name: game_tags g_id_game_tags_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.game_tags
    ADD CONSTRAINT g_id_game_tags_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: graphic_likes g_id_graphic_likes_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphic_likes
    ADD CONSTRAINT g_id_graphic_likes_fkey FOREIGN KEY (g_id) REFERENCES public.graphics(id) ON DELETE CASCADE NOT VALID;


--
-- Name: graphic_tags g_id_graphic_tags_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphic_tags
    ADD CONSTRAINT g_id_graphic_tags_fkey FOREIGN KEY (g_id) REFERENCES public.graphics(id) ON DELETE CASCADE NOT VALID;


--
-- Name: reviews g_id_reviews_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT g_id_reviews_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE;


--
-- Name: votes g_id_votes_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.votes
    ADD CONSTRAINT g_id_votes_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: awards membername_awards_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.awards
    ADD CONSTRAINT membername_awards_fkey FOREIGN KEY (membername) REFERENCES public.members(username) ON DELETE CASCADE NOT VALID;


--
-- Name: contest_nominations nominator_username_contest_nominations_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.contest_nominations
    ADD CONSTRAINT nominator_username_contest_nominations_fkey FOREIGN KEY (nominator_username) REFERENCES public.members(username) ON DELETE CASCADE NOT VALID;


--
-- Name: leaderboard pubkey_leaderboard_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.leaderboard
    ADD CONSTRAINT pubkey_leaderboard_fkey FOREIGN KEY (pubkey) REFERENCES public.games(g_id) ON DELETE CASCADE NOT VALID;


--
-- Name: games user_id_games_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT user_id_games_fkey FOREIGN KEY (user_id) REFERENCES public.members(userid) ON DELETE CASCADE NOT VALID;


--
-- Name: collection_games userid_collection_games_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.collection_games
    ADD CONSTRAINT userid_collection_games_fkey FOREIGN KEY (g_id) REFERENCES public.games(g_id) ON DELETE CASCADE;


--
-- Name: collections userid_collections_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.collections
    ADD CONSTRAINT userid_collections_fkey FOREIGN KEY (userid) REFERENCES public.members(userid) ON DELETE CASCADE;


--
-- Name: graphic_likes userid_graphic_likes_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphic_likes
    ADD CONSTRAINT userid_graphic_likes_fkey FOREIGN KEY (userid) REFERENCES public.members(userid) ON DELETE CASCADE NOT VALID;


--
-- Name: graphics userid_graphics_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.graphics
    ADD CONSTRAINT userid_graphics_fkey FOREIGN KEY (userid) REFERENCES public.members(userid) ON DELETE CASCADE NOT VALID;


--
-- Name: reviews userid_reviews_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT userid_reviews_fkey FOREIGN KEY (userid) REFERENCES public.members(userid) ON DELETE CASCADE;


--
-- Name: awards username_awards_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.awards
    ADD CONSTRAINT username_awards_fkey FOREIGN KEY (username) REFERENCES public.members(username) ON DELETE CASCADE NOT VALID;


--
-- Name: user_info username_user_info_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sploder
--

ALTER TABLE ONLY public.user_info
    ADD CONSTRAINT username_user_info_fkey FOREIGN KEY (username) REFERENCES public.members(username) ON DELETE CASCADE NOT VALID;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT ALL ON SCHEMA public TO sploder;


--
-- PostgreSQL database dump complete
--

\unrestrict 4AaagqC8O1GZmrPI2TvwEiCy8dQxSsRIqMKLOqUU82quyaFndC2NsZH9QmhdfOS

